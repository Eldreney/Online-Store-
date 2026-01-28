<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(){
      Auth::guard('admin')->user();

      echo "weclome to admin dashboard";
    }


public function logout(Request $request){
    Auth::guard('admin')->logout();
    return redirect()->route('admin.login');
    }


}
