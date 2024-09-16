<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\AvisoContactoMail;
use App\Mail\ContactMail;
use App\Models\Solicitudes;
use Illuminate\Support\Facades\Mail;

use function PHPUnit\Framework\isNull;

class ContactController extends Controller
{
    public function contactForm(ContactRequest $request){

        $language = $request->language ?: 'esp';

        $solicitud = new Solicitudes();
        $solicitud->name = $request->name;
        $solicitud->phone = $request->phone;
        $solicitud->email = $request->email;
        $solicitud->language = $language;
        $solicitud->save();

        Mail::to($request->email)->send(new ContactMail($request->name, $language));
        Mail::to('marcosles7485@gmail.com')->send(new AvisoContactoMail($request->name, $request->phone, $request->email,$language));

        return response()->json(['message' => 'success'], 200);
    }

}
