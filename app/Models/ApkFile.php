<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ApkFile extends Model
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
    public static $storageLocation = "/files/";

    public static function getRootPath()
    {
        if (!strcmp(ApkFile::$storageDriver, "local")) {
            return \Config::get("filesystems.disks.local.root") . ApkFile::$storageLocation;
        }
        return "";
    }

    public static function store(UploadedFile $file)
    {

        do {
            $filename = uniqid() . ".apk";// . "." . $file->getClientOriginalExtension();
        } while (\Storage::disk(ApkFile::$storageDriver)->exists($filename));

        $entry = ApkFile::create([
            'filename' => $filename,
            'mime' => $file->getClientMimeType(),
            'original_filename' => $file->getClientOriginalName()
        ]);
        \Storage::disk(ApkFile::$storageDriver)->put(ApkFile::$storageLocation . $filename, \File::get($file));

        return $entry;

//        $file->move(base_path() . Student::$consentFormPath, $filename);

    }

    public function deleteWithFile()
    {
        if (\Storage::disk(ApkFile::$storageDriver)->exists(ApkFile::$storageLocation . $this->filename))
            \Storage::disk(ApkFile::$storageDriver)->delete(ApkFile::$storageLocation . $this->filename);
        $this->delete();
    }

}
