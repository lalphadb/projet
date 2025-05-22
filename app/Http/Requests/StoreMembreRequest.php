<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMembreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'prenom'         => 'required|string|max:255',
            'nom'            => 'required|string|max:255',
            'email'          => 'nullable|email|max:255',
            'telephone'      => 'nullable|string|max:255',
            'date_naissance' => 'nullable|date',
            'sexe'           => 'nullable|in:H,F',
            'numero_rue'     => 'nullable|string|max:255',
            'nom_rue'        => 'nullable|string|max:255',
            'ville'          => 'nullable|string|max:100',
            'province'       => 'nullable|string|max:50',
            'code_postal'    => 'nullable|string|max:10',
            'ecole_id'       => 'nullable|exists:ecoles,id',
        ];
    }
}
