<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminAction;
use Validator;
use DataTables;


class HomeController extends Controller
{
    public function __construct(){
       

    }
    public function index()
    {
        return view('home');
       
    }
  
}
