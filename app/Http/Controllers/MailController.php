<?php

namespace App\Http\Controllers;

use App\Mail\MailMe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function index()
    {
        return view('email.send');
    }

    public function store()
    {
        $request = request()->validate([
            'email'     => 'required|email',
            'message'   => 'required|min:10',
        ]);

        $result = Mail::to(request('email'))
            ->send(new MailMe($request['message']));

        return redirect('email')
            ->with('message', 'Message sent!');
    }
}
