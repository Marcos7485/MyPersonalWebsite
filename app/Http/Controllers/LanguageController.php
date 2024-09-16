<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function LanguageSelector ($language){
        $languages = $language;
        $additionalInfo = 'Some additional information'; // Ejemplo de otra propiedad

        // Devuelve los datos en formato JSON
        return response()->json([
            'languages' => $languages,
            'additional_info' => $additionalInfo,
            'status' => 'success'
        ]);
    }
}
