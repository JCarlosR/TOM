<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Promotion;
use Illuminate\Http\Request;

use App\Http\Requests;
use Intervention\Image\Facades\Image;

class PromotionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected $messages = [
        'fan_page_id.required' => 'Es necesario seleccionar una fanpage.',
        'fan_page_id.exists' => 'La fan page indicada no existe.',
        'description.required' => 'Olvidó ingresar una descripción',
        'description.max' => 'La descripción excede los 180 caracteres permitidos.',
        'end_date.date' => 'Ingrese una fecha válidad.',
        'end_date.after' => 'Ingrese una fecha posterior a mañana.',
        'end_date.required_if' => 'Olvidó ingresar la fecha de vigencia.',
        'image.required' => 'Debe subir una imagen para su promoción.',
        'image.image' => 'La imagen subida no tiene un formato válido.',
        'attempts.required' => 'Debe indicar la frecuencia con que se gana la promoción.',
        'attempts.min' => 'El número de veces para ganar debe ser al menos 1.',
        'attempts.max' => 'El número de veces para ganar debe ser 10 como máximo.'
    ];

    public function edit($id)
    {
        $promotion = Promotion::findOrFail($id);
        $fanPage = $promotion->fanPage;
        return view('panel.promotion.edit')->with(compact('promotion', 'fanPage'));
    }

    public function store(Request $request)
    {
        $rules = [
            'fan_page_id' => 'required|exists:fan_pages,id',
            'description' => 'required|max:180',
            'infinite' => 'integer|min:0|max:1',
            'end_date' => 'required_if:infinite,0|date|after:tomorrow',
            'image' => 'required|image',
            'attempts' => 'required|min:1|max:10'
        ];

        $this->validate($request, $rules, $this->messages);

        // TODO: Validate if the fan page is associated with the user

        $promotion = Promotion::create([
            'fan_page_id' => $request->get('fan_page_id'),
            'description' => $request->get('description'),
            'end_date' => $request->get('infinite')==1 ? null : $request->get('end_date'),
            'image' => '',
            'attempts' => $request->get('attempts')
        ]);

        // TODO: Use transactions

        $extension = $request->file('image')->getClientOriginalExtension();
        $file_name = $promotion->id . '.' . $extension;

        $path = public_path('images/promotions/' . $file_name);

        Image::make($request->file('image'))
            ->fit(1280, 720, function ($c) {
                $c->upsize(); // don't resize smaller images
            })
            ->save($path);

        $promotion->image = $extension;
        $promotion->save();

        return redirect("promotion/$promotion->id/instructions");
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'description' => 'required|max:180',
            'infinite' => 'integer|min:0|max:1',
            'end_date' => 'required_if:infinite,0|date|after:tomorrow',
            'image' => 'image',
            'attempts' => 'required|min:1|max:10'
        ];

        $this->validate($request, $rules, $this->messages);

        $promotion = Promotion::findOrFail($id);
        $promotion->description = $request->get('description');
        $promotion->end_date = $request->get('infinite')==1 ? null : $request->get('end_date');
        $promotion->attempts = $request->get('attempts');

        if ($request->hasFile('image')) {
            $extension = $request->file('image')->getClientOriginalExtension();
            $file_name = $promotion->id . '.' . $extension;
            $path = public_path('images/promotions/' . $file_name);

            Image::make($request->file('image'))
                ->fit(1280, 720, function ($c) {
                    $c->upsize(); // don't resize smaller images
                })
                ->save($path);

            $promotion->image = $extension;
        }

        $promotion->save();
        session()->flash('message', 'Los datos de su promoción se han modificado exitosamente!');

        return redirect('config/page/'.$promotion->fanPage->id.'/promotions');
    }

    public function delete($id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->delete();

        session()->flash('message', 'La promoción seleccionada se ha dado de baja!');

        return back();
    }

}
