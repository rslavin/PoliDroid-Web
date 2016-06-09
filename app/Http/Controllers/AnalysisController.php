<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class AnalysisController extends Controller
{
    public function getConsistencyForm(){
        return view('forms.consistencyForm');
    }
}
