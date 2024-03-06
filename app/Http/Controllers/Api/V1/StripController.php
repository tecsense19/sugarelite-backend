<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;

class StripController extends BaseController
{
    public function index(Request $request){
        echo '<pre>';print_r("hi");echo '</pre>';
        die;
    }
}
