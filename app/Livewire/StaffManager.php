<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class StaffManager extends Component
{
    use WithPagination;

    public $first_name, $middle_name, $last_name, $username, $email, $password, $role, $phone, $address;

    protected $rules = [
        'first_name'  => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'last_name'   => 'required|string|max:255',
        'username'    => 'required|string|max:255|unique:users,username',
        'email'       => 'required|email|unique:users,email',
        'phone'       => 'nullable|string|max:20',
        'address'     => 'nullable|string|max:255',
        'password'    => 'required|string|min:8',
        'role'        => 'required|in:admin,registrar,cashier',
    ];

    public function saveStaff()
{
    $this->validate();

    DB::transaction(function () {
        // 1. Create the User account with the correct role
        $user = User::create([
            'name'     => $this->first_name . ' ' . $this->last_name,
            'email'    => $this->email,
            'password' => Hash::make($this->password),
            'role'     => $this->role, // This MUST be 'registrar', 'admin', or 'cashier'
            'status'   => 'Active',
        ]);

        // 2. Create the Employee record
        Employee::create([
            'first_name' => $this->first_name,
            'last_name'  => $this->last_name,
            'email'      => $this->email,
            'role'       => $this->role,
            'user_id'    => $user->id,
        ]);
    });

    session()->flash('success', 'Staff created successfully!');
}

    public function render()
    {
        $staff = User::whereIn('role', ['admin', 'registrar', 'cashier'])
                     ->latest()
                     ->paginate(10);

        return view('livewire.admin.staff-manager', [
            'staffList' => $staff
        ])->layout('components.layouts.admin');
    }
}