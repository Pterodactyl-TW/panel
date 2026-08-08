<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Hashids 組態設定
    |--------------------------------------------------------------------------
    |
    | 這裡是控制 Panel 中 Hashids 設定與使用方式的相關選項。
    |
    */
    'salt' => env('HASHIDS_SALT'),
    'length' => env('HASHIDS_LENGTH', 8),
    'alphabet' => env('HASHIDS_ALPHABET', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'),
];
