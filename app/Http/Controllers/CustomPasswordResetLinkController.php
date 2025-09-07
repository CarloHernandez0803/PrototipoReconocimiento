<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class CustomPasswordResetLinkController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'correo' => 'required|email',
        ], [
            'correo.required' => 'El campo correo electrónico es obligatorio.',
            'correo.email' => 'El correo electrónico debe ser una dirección válida.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $status = Password::broker('users')->sendResetLink(
            ['correo' => $request->correo]
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('correo'))
                            ->withErrors(['correo' => __($status)]);
    }
}