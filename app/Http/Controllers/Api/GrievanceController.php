<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class GrievanceController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'phone'   => 'nullable|string|max:15',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:2000',
        ]);

        $registrarEmail = Setting::get('contact_registrar_email', 'registrar@anmc.assam.gov.in');

        // TODO: Replace with a proper Mailable class for better templating
        Mail::raw(
            "Name: {$request->name}\nEmail: {$request->email}\nPhone: {$request->phone}\n\nSubject: {$request->subject}\n\nMessage:\n{$request->message}",
            function ($message) use ($request, $registrarEmail) {
                $message->to($registrarEmail)
                        ->subject('Grievance Submission: ' . $request->subject)
                        ->replyTo($request->email, $request->name);
            }
        );

        return response()->json([
            'message' => 'Your grievance has been submitted successfully. The Registrar will review and respond to your email within 7 working days.',
        ]);
    }
}
