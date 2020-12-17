<?php

namespace App\Http\Controllers;

use App\Mail\MailMe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class MailController extends Controller
{
    public function index()
    {
        return view('email.send');
    }

    public function send(Request $request)
    {
        $rules = [
            'email'     => ['required', 'email'],
            'message'   => ['required', 'min:10'],
        ];

        $data = $request->input();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => [
                    'code'       => 422,
                    'message'    => "Validation error",
                    'errors'     => $validator->errors(),
                ],
            ], 422);
        }

        $sendMessage = Mail::to(request('email'))
            ->send(new MailMe($request['message']));

        return response()->json()->setStatusCode(200);
    }
}
