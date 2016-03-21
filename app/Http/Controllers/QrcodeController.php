<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class QrcodeController extends Controller
{
    public function qrCode(){
        return view('test.qrcode');
    }
}
