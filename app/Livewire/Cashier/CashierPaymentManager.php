<?php

namespace App\Livewire\Cashier;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payment;
use App\Models\User;

class CashierPaymentManager extends Component
{
    use WithPagination;

    public $showModal = false;
    public $isEditMode = false;
    public $activeDropdown = null;

    public $payment_id;
    public $payment_user_id;
    public $payment_amount = 500;
    public $payment_type = 'Cash';
    public $payment_reference_no = '';

    public function toggleDropdown($id)
    {
        $this->activeDropdown = $this->activeDropdown === $id ? null : $id;
    }

    public function closeDropdowns()
    {
        $this->activeDropdown = null;
    }

    public function openCreateModal()
    {
        $this->resetValidation();
        $this->isEditMode = false;
        $this->payment_id = null;
        $this->payment_user_id = '';
        $this->payment_amount = 500;
        $this->payment_type = 'Cash';
        $this->payment_reference_no = '';
        $this->showModal = true;
        $this->closeDropdowns();
    }

    public function editPayment($id)
    {
        $this->resetValidation();
        $this->isEditMode = true;
        $payment = Payment::find($id);
        $this->payment_id = $payment->id;
        $this->payment_user_id = $payment->user_id;
        $this->payment_amount = $payment->amount;
        $this->payment_type = $payment->payment_method ?? 'Cash';
        $this->payment_reference_no = $payment->transaction_id;
        $this->showModal = true;
        $this->closeDropdowns();
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function savePayment()
    {
        $this->validate([
            'payment_user_id' => 'required',
            'payment_amount' => 'required|numeric',
        ]);

        if ($this->isEditMode) {
            Payment::find($this->payment_id)->update([
                'user_id' => $this->payment_user_id,
                'amount' => $this->payment_amount,
                'payment_method' => $this->payment_type,
                'transaction_id' => $this->payment_reference_no,
            ]);
            session()->flash('success', 'Payment updated successfully.');
        } else {
            Payment::create([
                'user_id' => $this->payment_user_id,
                'amount' => $this->payment_amount,
                'payment_method' => $this->payment_type,
                'transaction_id' => $this->payment_reference_no,
                'status' => 'Paid'
            ]);
            session()->flash('success', 'Payment processed successfully.');
        }

        $this->closeModal();
    }

    public function markAsPaid($id)
    {
        $payment = Payment::find($id);
        if ($payment) {
            $payment->status = 'Paid';
            $payment->save();
            session()->flash('success', 'Payment marked as Paid.');
        }
        $this->closeDropdowns();
    }

    public function render()
    {
        return view('livewire.cashier.cashier-payment-manager', [
            'payments' => Payment::with(['user'])->latest()->paginate(10),
            'students' => User::where('role', 'student')->get(),
        ])->layout('components.layouts.cashier');
    }
}
