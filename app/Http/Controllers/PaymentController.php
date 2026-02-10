<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        // TODO: Add logic to save payment details

        return redirect()->route('student.dashboard')->with('success', 'Payment recorded successfully!');
    }
}
