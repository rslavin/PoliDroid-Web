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
                        if($results === "No violations detected")
                            $consistency->is_consistent = 1;
                        if(!$results || strpos($results, 'OpenJDK') !== false)
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
        return view('tools.sourceAnalyzer')->with('title', 'Source Code Analyzer');
    }

    private static function pvDetect($policyFile, $flowDroidOut) {
        $pvRoot = AnalysisController::$pvDir;
        $pvDetector = AnalysisController::$pvJar;
        $owl = AnalysisController::$owlFile;
        $mapping = AnalysisController::$mappingFile;

        exec("cd $pvRoot && java -jar $pvDetector $owl $mapping $policyFile $flowDroidOut", $out);

        return implode("\n", $out);
    }
}
