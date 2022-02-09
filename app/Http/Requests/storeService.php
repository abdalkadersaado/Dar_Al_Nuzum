<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeService extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_en' => ['required', 'unique:services,name_en'],
            'name_ar' => ['required', 'unique:services,name_ar'],
            'description_ar' => ['required'],
            'description_en' => ['required'],
        ];
    }
}
