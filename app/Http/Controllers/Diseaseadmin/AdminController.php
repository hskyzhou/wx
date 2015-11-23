<?php

namespace App\Http\Controllers\DiseaseAdmin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Menu;

class AdminController extends Controller
{
    public function __construct(){
    	$this->middleware('permission:admin.page.show');
    }

    public function getIndex(){
    	return view('admin.show');
    }
}
