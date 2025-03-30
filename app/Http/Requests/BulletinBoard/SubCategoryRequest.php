<?php

namespace App\Http\Requests\BulletinBoard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubCategoryRequest extends FormRequest
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
      // existsでIDの存在確認
      'main_category_id' => 'required|exists:main_categories,id',
      //サブカテゴリー
      'sub_category_name' => 'required|string|max:100|unique:sub_categories,sub_category',
    ];
  }

  public function messages()
  {
    return [
      'sub_category_name.required' => 'サブカテゴリーの入力は必須です。',
      'sub_category_name.string' => 'サブカテゴリーは文字列である必要があります。',
      'sub_category_name.max' => 'サブカテゴリーは100文字以内で入力してください。',
      'sub_category_name.unique' => 'このサブカテゴリー名はすでに存在しています。',
    ];
  }
}
