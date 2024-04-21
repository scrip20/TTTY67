<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = $user->orders; // Asume que tienes una relación 'orders' en tu modelo User

        return view('orders.index', compact('orders'));
    }
}