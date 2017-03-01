<?php

namespace App\Http\Controllers\Creator;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class EarningController extends Controller
{
    public function index()
    {
        return view('panel.referral.earnings.index');
    }

    public function postPaypalAccount(Request $request)
    {
        $rules = [
            'paypal_account' => 'required|email'
        ];
        $messages = [
            'paypal_account.required' => 'Olvidó ingresar su cuenta de Paypal.',
            'paypal_account.email' => 'Ingrese un email válido.'
        ];
        $this->validate($request, $rules, $messages);

        // Update the paypal account info
        $user = auth()->user();
        $user->paypal_account = $request->input('paypal_account');
        $user->save();

        return back()->with('notification', 'Sus datos se actualizaron correctamente!');
    }
}
