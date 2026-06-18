<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class CommunicationController extends Controller
{
    public function announcements()
    {
        $announcements = Announcement::with('author')->latest()->get();
        return view('communication.announcements', compact('announcements'));
    }

    public function storeAnnouncement(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_role' => 'required|string',
        ]);

        $validated['author_id'] = Auth::id();
        Announcement::create($validated);

        return redirect()->back()->with('success', 'Announcement posted successfully.');
    }

    public function messages()
    {
        $userId = Auth::id();
        $messages = Message::with(['sender', 'receiver'])
            ->where('receiver_id', $userId)
            ->orWhere('sender_id', $userId)
            ->latest()
            ->get();
            
        return view('communication.messages', compact('messages'));
    }

    public function storeMessage(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
        ]);

        $validated['sender_id'] = Auth::id();
        Message::create($validated);

        return redirect()->back()->with('success', 'Message sent successfully.');
    }
}
