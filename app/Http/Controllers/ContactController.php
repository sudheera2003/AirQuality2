<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        // Validate input fields
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email',
            'phone' => 'required|string|max:15',
            'subject' => 'required|string',
            'message' => 'required|string|min:10',
        ]);

        // Example: Save to database or send an email
        Mail::raw("New Contact Message from {$request->first_name} {$request->last_name}.\n\n{$request->message}", function ($mail) use ($request) {
            $mail->to('sudheeradilum@gmail.com')
                 ->subject("New Contact Message - {$request->subject}")
                 ->replyTo($request->email);
        });

        return response()->json(['success' => 'Message sent successfully!']);
    }
}

