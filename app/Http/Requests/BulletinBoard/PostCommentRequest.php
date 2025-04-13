<?php

namespace App\Http\Requests\BulletinBoard;

use Illuminate\Foundation\Http\FormRequest;

class PostCommentRequest extends FormRequest
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
      'comment' => 'required|string|max:250',
      'post_id' => 'required|integer|exists:posts,id'
    ];
  }

  public function messages()
  {
    return [
      'comment.required' => '入力は必須です。',
      'comment.string' => 'コメントは文字列である必要があります。',
      'comment.max' => '250字以内で入力してください'
    ];
  }
}
