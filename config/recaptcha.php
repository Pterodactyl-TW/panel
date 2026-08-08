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
    'secret_key' => env('RECAPTCHA_SECRET_KEY', '6Ld73XotAAAAALO-paiy8zhcPoMOzUaHCRUBkU88'),
    '_shipped_secret_key' => '6Ld73XotAAAAALO-paiy8zhcPoMOzUaHCRUBkU88',

    /*
     * 使用自訂的網站金鑰，預設會使用我們的公開金鑰
     */
    'website_key' => env('RECAPTCHA_WEBSITE_KEY', '6Ld73XotAAAAAF_Xac2jPJWNJTG_2sgPp2qz_QEK'),
    '_shipped_website_key' => '6Ld73XotAAAAAF_Xac2jPJWNJTG_2sgPp2qz_QEK',

    /*
     * 網域驗證預設為啟用，會比對解決驗證碼時使用的網域，
     * 因為公開金鑰在 Google 端顯然無法啟用網域驗證。
     */
    'verify_domain' => true,
];
