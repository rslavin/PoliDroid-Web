<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PolicyFile extends Model
{
    protected $fillable = [
        'filename',
        'mime',
        'original_filename',
    ];
    
    public function consistencyCheck(){
        return $this->hasOne('App\Models\ConsistencyCheck');
    }

    public static $storageDriver = "local";
    public static $storageLocation = "/files/policies/";

    public static function getRootPath()
    {
        if (!strcmp(PolicyFile::$storageDriver, "local")) {
            return \Config::get("filesystems.disks.local.root") . PolicyFile::$storageLocation;
        }
        return "";
    }

    public static function store($policyText)
    {

        do {
            $filename = uniqid() . ".policy";// . "." . $file->getClientOriginalExtension();
        } while (\Storage::disk(PolicyFile::$storageDriver)->exists($filename));

        $entry = PolicyFile::create([
            'filename' => $filename,
        ]);
        \Storage::disk(PolicyFile::$storageDriver)->put(PolicyFile::$storageLocation . $filename, $policyText);

        return $entry;

//        $file->move(base_path() . Student::$consentFormPath, $filename);

    }

    public function getText(){
        return \Storage::disk(PolicyFile::$storageDriver)->get(PolicyFile::$storageLocation . $this->filename);
    }

    public function deleteWithFile()
    {
        if (\Storage::disk(PolicyFile::$storageDriver)->exists(PolicyFile::$storageLocation . $this->filename))
            \Storage::disk(PolicyFile::$storageDriver)->delete(PolicyFile::$storageLocation . $this->filename);
        $this->delete();
    }

}
