<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        // Mock data matching your screenshot exactly
        $payments = [
            [
                'id' => 10,
                'username' => 'Novicio',
                'full_name' => 'John Laurence E Novicio',
                'email' => 'novicio@example.com',
                'amount' => '1,000.00',
                'date' => '2026-01-28 13:30:10',
                'status' => 'Completed'
            ],
            [
                'id' => 9,
                'username' => 'Novicio',
                'full_name' => 'John Laurence E Novicio',
                'email' => 'novicio@example.com',
                'amount' => '1,000.00',
                'date' => '2026-01-28 12:38:58',
                'status' => 'Completed'
            ],
            [
                'id' => 8,
                'username' => 'Novicio',
                'full_name' => 'John Laurence E Novicio',
                'email' => 'novicio@example.com',
                'amount' => '1,000.00',
                'date' => '2026-01-28 12:34:00',
                'status' => 'Pending'
            ],
            [
                'id' => 7,
                'username' => 'Novicio',
                'full_name' => 'John Laurence E Novicio',
                'email' => 'novicio@example.com',
                'amount' => '1,000.00',
                'date' => '2026-01-28 04:26:51',
                'status' => 'Pending'
            ],
            [
                'id' => 6,
                'username' => 'Student',
                'full_name' => 'Crisjan C. Poliquit',
                'email' => 'student@example.com',
                'amount' => '1,000.00',
                'date' => '2026-01-28 04:07:54',
                'status' => 'Pending'
            ],
            // ... adding a few more to simulate the list
            [
                'id' => 5, 'username' => 'Student', 'full_name' => 'Crisjan C. Poliquit', 'email' => 'student@example.com', 'amount' => '1,000.00', 'date' => '2026-01-28 03:42:36', 'status' => 'Pending'
            ],
            [
                'id' => 4, 'username' => 'Student', 'full_name' => 'Crisjan C. Poliquit', 'email' => 'student@example.com', 'amount' => '1,000.00', 'date' => '2026-01-28 02:48:11', 'status' => 'Pending'
            ],
            [
                'id' => 3, 'username' => 'Student', 'full_name' => 'Crisjan C. Poliquit', 'email' => 'student@example.com', 'amount' => '1,000.00', 'date' => '2026-01-28 01:39:13', 'status' => 'Pending'
            ],
            [
                'id' => 2, 'username' => 'Student', 'full_name' => 'Crisjan C. Poliquit', 'email' => 'student@example.com', 'amount' => '1,000.00', 'date' => '2026-01-28 01:33:50', 'status' => 'Pending'
            ],
            [
                'id' => 1, 'username' => 'Student', 'full_name' => 'Crisjan C. Poliquit', 'email' => 'student@example.com', 'amount' => '1,000.00', 'date' => '2026-01-28 01:22:37', 'status' => 'Pending'
            ],
        ];

        return view('admin.payments.index', compact('payments'));
    }
}
