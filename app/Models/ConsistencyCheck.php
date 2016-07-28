<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsistencyCheck extends Model
{
    public static $storageDriver = "local";
    public static $storageLocation = "/files/";
    
    protected $fillable = [
        'email', 'policy'
    ];
    
    public function apkFile(){
        return $this->belongsTo('App\Models\ApkFile');
    }

    public function policyFile(){
        return $this->belongsTo('App\Models\PolicyFile');
    }
    
}
