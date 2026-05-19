<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class update extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'between:0,100000'],
            'image' => ['nullable', 'image', 'mimes:png,jpeg'],
            'season_ids' => ['required', 'array', 'min:1'],
            'season_ids.*' => ['integer', 'exists:seasons,id'],
            'description' => ['required', 'string', 'max:120'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'price.required' => '値段を入力してください',
            'price.numeric' => '数字で入力してください',
            'price.between' => '0～100000円以内で入力してください',
            'image.image' => '「.png」または「.jpeg」形式でアップロードしてください',
            'image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
            'season_ids.required' => '季節を選択してください',
            'season_ids.array' => '季節を選択してください',
            'season_ids.min' => '季節を選択してください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '120文字以内で入力してください',
        ];
    }
}
