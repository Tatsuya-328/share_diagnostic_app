<?php
namespace App\Validator;

use Illuminate\Contracts\Translation\Translator;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Route;

class CustomValidator extends Validator
{
    public function __construct(Translator $translator, array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        $messages['required'] = 'このフィールドを入力してください';
        $messages['styles.required'] = "スタイルを選択してください";
        $messages['unique'] = 'すでに登録されています';
        $messages['password.confirmed'] = 'パスワードが確認用と一致しません';
        $messages['min'] = ":min文字以上で入力して下さい。";
        $messages['max'] = ":max文字以下で入力して下さい。";
        $messages['admission_fee.min'] = ":min以上の値を入力して下さい。";
        $messages['password.regex'] = "半角数字とアルファベットの混合したパスワードを入力して下さい。";
        $messages['email'] = "正しいメールアドレスを入力してください";
        $messages['email.exists'] = '該当するユーザーが存在しません。';
        $messages['email'] = '正しいメールアドレスを入力して下さい。';
        $messages['passwords.user'] = '該当するユーザーが存在しません。';
        $messages['digits'] = ':digits桁の半角数字を入力して下さい。';
        $messages['integer'] = '整数を入力して下さい';
        $messages['digits_between'] = ":min_digits桁から:max_digits桁の半角数字で入力して下さい";
        $messages['banned_email'] = "このメールアドレスは使用できません";
        $messages['duplicated_member'] = "すでに編集メンバーに登録されています";
        $messages['url'] = "正しいURLを入力して下さい";
        $messages['image'] = "画像をアップロードして下さい";
        $messages['string'] = '形式が不正です';
        $messages['dimensions'] = ':min_width x :min_height以上の画像をアップロードしてください';
        parent::__construct($translator, $data, $rules, $messages, $customAttributes);
    }
}
