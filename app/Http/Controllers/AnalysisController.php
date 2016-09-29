<?php

namespace App\Http\Controllers;

use App\Models\ApkFile;
use App\Models\ConsistencyCheck;
use App\Models\PolicyFile;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Yaml\Tests\A;

class AnalysisController extends Controller {
    public static $flowdroidDir = "/opt/FlowDroid";
    public static $pvDir = "/opt/PVDetector";
    public static $pvJar = "PrivacyViolationDetection.jar";
    public static $owlFile = "ontology.owl";
    public static $mappingFile = "mappings.csv";
    public static $inputFileDir = "/input";
    public static $sampleSource = "/* Sample Android Code */
package codepath.apps.demointroandroid;

import android.os.Bundle;
import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.Intent;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.Toast;

public class ActionBarMenuActivity extends Activity {

	@SuppressLint(\"NewApi\")
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_action_bar_menu);
		getActionBar().setTitle(\"Click an Icon\");
		getAltitude();
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.activity_action_bar_menu, menu);
		return true;
	}
	
	
	@Override
	public boolean onOptionsItemSelected(MenuItem item) {
	  switch (item.getItemId()) {
	  case R.id.menu_toast:
		Toast.makeText(this, \"Toasted\", Toast.LENGTH_SHORT).show();
		break;
	  case R.id.menu_launch:
		Intent i = new Intent(this, SimpleBundleDemoActivity.class);
		startActivity(i);
		break;
	  default:
		break;
	  }
	  return true;
	}
	

}";

    public function getConsistencyForm() {
        return view('tools.pvDetectorForm')->with('title', 'PVDetector');
    }

    public function postConsistencyForm(Request $request) {
        $this->validate($request, [
            'apk_file' => 'required',
            'policy' => 'required|max:16777215',
            'email' => 'required|email|max:64'
        ]);

        $apk = ApkFile::store(Input::file('apk_file'));
        $policy = PolicyFile::store(Input::get('policy'));

        $consistency = new ConsistencyCheck();
        $consistency->email = Input::get('email');
        // TODO policy needs to be stored as a file because of PVDetector program
        $consistency->policy_file_id = $policy->id;
        $consistency->apk_file_id = $apk->id;
        $consistency->save();

        return redirect("pvdetector")->with('success', true);
    }

    /**
     * Looks for a ConsistencyCheck that has not been analyzed. If found, starts analysis.
     */
    public static function checkForJob() {
        // TODO make sure there are no jobs running (has_started == 1 and is_complete == 0)
        // TODO may need to add a timeout to the exec somehow.
        if ($check = ConsistencyCheck::where('has_started_scan', '=', 0)->first())
            AnalysisController::doJob($check);
    }

    /**
     * @deprecated
     */
    public static function checkForFinishedJobsOLD() {
        // look for log file with name of apk file. email the user
        $path = ApkFile::getRootPath() . "out";
        $files = \File::files($path);
        foreach ($files as $file) {
            $filename = pathinfo($file)['filename'];
            $fileWithPath = "$path/$filename";

            $apkFile = ApkFile::where('filename', '=', $filename)
                ->whereHas('consistencyCheck', function ($subQuery) {
                    $subQuery->where('is_complete', '=', 0);
                })->first();
            if ($apkFile) {
                $consistency = $apkFile->consistencyCheck;
                $consistency->results = file_get_contents($fileWithPath);
                $consistency->is_complete = 1;
                // TODO add consistent flag
                $consistency->save();
                // TODO email user results, maybe parse them first
            }

            // remove the file
//            \File::delete($fileWithPath);
        }
    }

    /**
     * Looks for completed analyses and reports/stores results.
     */
    public static function checkForFinishedJobs() {
        // look for an ApkFile that needs to be analyzed
        $apkFiles = ApkFile::whereHas('consistencyCheck', function ($subQuery) {
            $subQuery->where('is_complete', '=', 0)
                ->where('has_started_scan', '=', 1);
        })->get();
        if ($apkFiles) {
            $path = ApkFile::getOutPath();
            $files = \File::files($path);
        } else
            return;

        foreach ($apkFiles as $apkFile) {
            foreach ($files as $file) {
                $fileInfo = pathinfo($file);
                $filename = "$fileInfo[filename].$fileInfo[extension]";

                // find file
                if (!strcmp($filename, $apkFile->filename)) {
                    $fp = fopen($path . "/" . $filename, "r");
                    if (flock($fp, LOCK_EX)) { // check if the file is still open by flowdroid
                        $fileWithPath = "$path/$filename";
                        $consistency = $apkFile->consistencyCheck;
                        // run detection
                        $results = AnalysisController::pvDetect($consistency->policyFile->getPath(), ApkFile::getOutPath() . $consistency->apkFile->filename);
//                        $consistency->results = file_get_contents($fileWithPath);
                        $consistency->results = $results;
                        if ($results === "No violations detected")
                            $consistency->is_consistent = 1;
                        if (!$results || strpos($results, 'OpenJDK') !== false)
                            $consistency->results = "An error occurred.";
                        $consistency->is_complete = 1;
                        $consistency->save();
                        \Mail::send('emails.pvResults', ['filename' => $apkFile->original_filename, 'results' => $consistency->results], function ($m) use ($consistency) {
                            $m->from('donotreply@polidroid.org', 'PoliDroid');
                            $m->to($consistency->email)->subject('PVDetector Results');
                        });
                    }
                    // remove the file
//                  \File::delete($fileWithPath);
                    break;
                }
            }

        }
    }


    /**
     * Runs FlowDroid on ConsistencyCheck object.
     * @param ConsistencyCheck $check
     */
    public static function doJob(ConsistencyCheck $check) {
        $rootPath = ApkFile::getRootPath();
        $outPath = ApkFile::getOutPath();
        $filename = $check->apkFile->filename;

        exec("cd " . AnalysisController::$flowdroidDir . " && java -Xms65536m -Xmx196608m  -cp " .
            "soot-trunk.jar:soot-infoflow.jar:soot-infoflow-android.jar:slf4j-api-1.7.5.jar:axml-2.0.jar " .
            "soot.jimple.infoflow.android.TestApps.Test " . $rootPath . $filename . " " .
            "android.jar --nostatic --aliasflowins --layoutmode none > " . $outPath . $filename . " 2>&1");
        $check->has_started_scan = 1;
        $check->save();
    }

    public function getSourceAnalyzer() {
        $data['title'] = 'Source Code Analyzer';
        $data['sampleCode'] = AnalysisController::$sampleSource;
        $data['mappings'] = self::getMappingsAsJson();
        return view('tools.sourceAnalyzer', $data);
    }

    private static function pvDetect($policyFile, $flowDroidOut) {
        $pvRoot = AnalysisController::$pvDir;
        $pvDetector = AnalysisController::$pvJar;
        $owl = AnalysisController::$owlFile;
//        $mapping = AnalysisController::$mappingFile;
        $mapping = public_path() . "/" . self::$inputFileDir . "/" . AnalysisController::$mappingFile;

        exec("cd $pvRoot && java -jar $pvDetector $owl $mapping $policyFile $flowDroidOut", $out);

        return implode("\n", $out);
    }

    private static function getMappingsAsJson() {
        return json_encode(self::parseMappings(public_path() . "/" . self::$inputFileDir . "/" . AnalysisController::$mappingFile));

    }

    private static function parseMappings($file, $delimiter = ",") {

        if (($handle = fopen($file, 'r')) !== FALSE) {
            $i = 0;
            while (($lineArray = fgetcsv($handle, 4000, $delimiter, '"')) !== FALSE) {
                $arr[$i][0] = $lineArray[0];
                preg_match('/.+\s([^\s]+)\(/', $lineArray[1], $matches);
                $arr[$i][1] = $matches[1];
                $i++;
            }
            fclose($handle);
        }

        // combine
        if (isset($arr)) {
            $combined = [];
            foreach ($arr as $pair) {
                if (array_key_exists($pair[0], $combined)) {
                    $combined[$pair[0]] = array_merge($combined[$pair[0]], [$pair[1]]);
                } else {
                    $combined[$pair[0]] = [$pair[1]];
                }
            }
        }

        // add labels
        if (isset($combined)) {
            $final = [];
            $i = 0;
            foreach ($combined as $phrase => $methods) {
                $final[$i]['phrase'] = $phrase;
                $final[$i]['methods'] = $methods;
                $i++;
            }

            return $final;
        }
        return null;
    }
}
