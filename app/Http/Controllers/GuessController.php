<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Mail;

class GuessController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function invitedBy($slug, $id)
    {
        // check if the user exists
        $user = User::findOrFail($id);
        // check if the slug is associated with the name of the user
        $slug_name = str_slug($user->name, '-');
        if ($slug != $slug_name)
            return redirect('/');

        $affiliate_to = $id;
        // set session variable
        session([
            'affiliate_to' => $affiliate_to
        ]);
        return view('welcome');
    }

    public function getStories()
    {
        return view('guess.stories');
    }

    public function getContact()
    {
        return view('guess.contact');
    }

    public function postContact(Request $request)
    {
        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'min:6',
            'content' => 'required|min:5'
            // 'postal_code' => ''
        ];
        $messages = [
            'name.required' => 'Olvidaste ingresar tu nombre.',
            'name.min' => 'El nombre ingresado es demasiado corto.',
            'email.required' => 'Olvidaste ingresar tu e-mail.',
            'email.email' => 'El e-mail ingresado no tiene un formato adecuado.',
            'phone.min' => 'Por favor ingresar un teléfono válido.',
            'content.required' => 'Olvidaste ingresar un mensaje.',
            'content.min' => 'El mensaje a enviar es demasiado corto.'
        ];
        $this->validate($request, $rules, $messages);

        // Send mail
        Mail::send('emails.contact', $request->all(), function ($m) {
            $m->to('hola@tombofans.com', 'Juan Ramos')->subject('Contacto - TomboFans');
        });

        return back()->with('notification', 'Tu mensaje se ha enviado correctamente. Te contactaremos muy pronto.');
    }

    public function privacy()
    {
        return view('guess.privacy');
    }
    public function terms()
    {
        return view('guess.terms');
    }

}
