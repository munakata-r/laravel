<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        // ユーザーがこのリクエストを承認されているかどうかを判定
        // ここではtrueを返しておく
        return true;
    }

    public function rules()
    {
        return [
            'product_name' => 'required|string|max:255',
            'company_id' => 'required|integer',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'comment' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'product_name.required' => '商品名は必須です。',
            'product_name.string' => '商品名は文字列でなければなりません。',
            'product_name.max' => '商品名は255文字以内でなければなりません。',
            'company_id.required' => 'メーカー名は必須です。',
            'company_id.integer' => 'メーカー名は整数でなければなりません。',
            'price.required' => '価格は必須です。',
            'price.numeric' => '価格は数値でなければなりません。',
            'stock.required' => '在庫数は必須です。',
            'stock.numeric' => '在庫数は数値でなければなりません。',
            'comment.string' => 'コメントは文字列でなければなりません。',
            'image.image' => '画像ファイルを指定してください。',
            'image.max' => '画像ファイルは2MB以下でなければなりません。',
        ];
    }
}
