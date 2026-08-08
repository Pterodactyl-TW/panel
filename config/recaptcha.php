<?php

return [
    /*
     * 啟用或停用驗證碼（captcha）
     */
    'enabled' => env('RECAPTCHA_ENABLED', true),

    /*
     * reCAPTCHA 驗證用的 API 端點。你不應該修改此設定。
     */
    'domain' => env('RECAPTCHA_DOMAIN', 'https://www.google.com/recaptcha/api/siteverify'),

    /*
     * 使用自訂的密鑰，預設會使用我們的公開密鑰
     */
    'secret_key' => env('RECAPTCHA_SECRET_KEY', '6LcJcjwUAAAAALOcDJqAEYKTDhwELCkzUkNDQ0J5'),
    '_shipped_secret_key' => '6LcJcjwUAAAAALOcDJqAEYKTDhwELCkzUkNDQ0J5',

    /*
     * 使用自訂的網站金鑰，預設會使用我們的公開金鑰
     */
    'website_key' => env('RECAPTCHA_WEBSITE_KEY', '6LcJcjwUAAAAAO_Xqjrtj9wWufUpYRnK6BW8lnfn'),
    '_shipped_website_key' => '6LcJcjwUAAAAAO_Xqjrtj9wWufUpYRnK6BW8lnfn',

    /*
     * 網域驗證預設為啟用，會比對解決驗證碼時使用的網域，
     * 因為公開金鑰在 Google 端顯然無法啟用網域驗證。
     */
    'verify_domain' => true,
];
