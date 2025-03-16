<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    // バリデーション
    public function rules()
    {
        return [
            //
            'over_name' => 'required|string|max:10',
            'under_name' => 'required|string|max:10',
            'over_name_kana' => 'required|string|regex:/^[ァ-ヴー]+$/u|max:30',
            'under_name_kana' => 'required|string|regex:/^[ァ-ヴー]+$/u|max:30',
            'mail_address' => 'required|email|unique:users,mail_address|max:100',
            'sex' => 'required|in:1,2,3',
            'old_year' => 'required|min:2000|max:' . date('Y'),
            'old_month' => 'required|between:1,12',
            'old_day' => 'required|between:1,31',
            'role' => 'required|in:1,2,3',
            'password' => 'required|min:8|max:30|confirmed'
        ];
    }
    // 日付の妥当性を確認
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $year = $this->input('old_year');
            $month = $this->input('old_month');
            $day = $this->input('old_day');
            // 正しい日付か確認
            if (!checkdate((int)$month, (int)$day, (int)$year)) {
                $validator->errors()->add('old_day', '正しい日付を入力してください');
            }
        });
    }
    // エラーメッセージ
    public function messages()
    {
        return [
            // 姓
            'over_name.required' => '姓の入力は必須です。',
            'over_name.string' => '文字列で入力してください。',
            'over_name.max' => '10文字以下で入力してください。',
            // 名
            'under_name.required' => '姓の入力は必須です。',
            'under_name.string' => '文字列で入力してください。',
            'under_name.max' => '10文字以下で入力してください。',
            // 姓カタカナ
            'over_name_kana.required' => 'セイの入力は必須です。',
            'over_name_kana.string' => '文字列で入力してください。',
            'over_name_kana.regex' => 'カタカナで入力してください。',
            'over_name_kana.max' => '30文字以下で入力してください。',
            // 名カタカナ
            'under_name_kana.required' => 'セイの入力は必須です。',
            'under_name_kana.string' => '文字列で入力してください。',
            'under_name_kana.regex' => 'カタカナで入力してください。',
            'under_name_kana.max' => '30文字以下で入力してください。',
            // メール
            'mail_address.required' => 'メールアドレスの入力は必須です。',
            'mail_address.email' => 'メールアドレスの形式で入力してください。',
            'mail_address.unique:users,mail_address' => 'このメールアドレス既に登録されています。',
            'mail_address.max' => '100文字以内で入力してください。',
            // 性別
            'sex.required' => '性別の選択は必須です。',
            // 生年月日
            'old_year.required' => '年は必須項目です。',
            'old_year.min' => '2000年以降を入力してください。',
            'old_year.max' => '未来の年は指定できません。',

            'old_month.required' => '月は必須項目です。',
            'old_month.between' => '月は1〜12の範囲で入力してください。',

            'old_day.required' => '日は必須項目です。',
            'old_day.between' => '日は1〜31の範囲で入力してください。',
            'old_day.date' => '正しい日付を入力してください。',
            // 役職
            'role.required' => '役職の選択は必須です。',
            // パスワード
            'password.required' => 'パスワードの入力は必須です。',
            'password.min' => 'パスワードは8文字以上で入力してください。',
            'password.max' => 'パスワードは20文字以内で入力してください。',
            'password.confirmed' => 'パスワードが一致しません。',
        ];
    }
}
