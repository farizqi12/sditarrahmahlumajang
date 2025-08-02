<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index(){
        // Logic to display wallet information
        return view('admin.wallet');
    }
}
