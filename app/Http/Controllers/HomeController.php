<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index(){
      return view("auth.index"); 
    }  
    public function home(){
      return view("home"); 
    }
    public function cliente(){

       $empresa = Auth::user()->empresa;
      return view("clients.home",compact('empresa')); 
    }
}