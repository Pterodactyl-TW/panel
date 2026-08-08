<?php

return [
    'sign_in' => '登入',
    'go_to_login' => '前往登入頁面',
    'failed' => '找不到符合這些登入資訊的帳號。',

    'forgot_password' => [
        'label' => '忘記密碼？',
        'label_help' => '請輸入你帳號的電子郵件地址，我們將寄送重設密碼的說明給你。',
        'button' => '復原帳號',
    ],

    'reset_password' => [
        'button' => '重設密碼並登入',
    ],

    'two_factor' => [
        'label' => '雙重驗證權杖',
        'label_help' => '此帳號需要第二層驗證才能繼續。請輸入你的裝置產生的驗證碼以完成登入。',
        'checkpoint_failed' => '雙重驗證權杖無效。',
    ],

    'throttle' => '登入嘗試次數過多，請於 :seconds 秒後再試一次。',
    'password_requirements' => '密碼長度至少須為 8 個字元，且應為此網站專屬的獨特密碼。',
    '2fa_must_be_enabled' => '管理員已要求此帳號必須啟用雙重驗證才能使用 Panel。',
];
