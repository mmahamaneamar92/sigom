<?php

namespace App\Http\Requests;

use App\Models\Materiel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MaterielUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'designation' => ['designation', 'max:255', Rule::unique(Materiel::class)->ignore($this->materiel()->designation)],
        ];
    }
}
