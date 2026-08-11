<?php

return [
    'location' => [
        'no_location_found' => '找不到符合所提供短碼的紀錄。',
        'ask_short' => '位置短碼',
        'ask_long' => '位置描述',
        'created' => '已成功建立新位置（:name），ID 為 :id。',
        'deleted' => '已成功刪除請求的位置。',
    ],
    'user' => [
        'search_users' => '請輸入使用者名稱、使用者 ID 或電子郵件地址',
        'select_search_user' => '要刪除的使用者 ID（輸入「0」以重新搜尋）',
        'deleted' => '使用者已成功從 Panel 刪除。',
        'confirm_delete' => '你確定要從 Panel 刪除此使用者嗎？',
        'no_users_found' => '找不到符合搜尋條件的使用者。',
        'multiple_found' => '提供的使用者條件找到多筆帳號，因啟用了 --no-interaction 選項，無法刪除使用者。',
        'ask_admin' => '這個使用者是管理員嗎？',
        'ask_email' => '電子郵件地址',
        'ask_username' => '使用者名稱',
        'ask_name_first' => '名字',
        'ask_name_last' => '姓氏',
        'ask_password' => '密碼',
        'ask_password_tip' => '若你想建立一組隨機密碼並以電子郵件寄送給使用者，請重新執行此指令（CTRL+C）並加上 `--no-password` 選項。',
        'ask_password_help' => '密碼長度至少須為 8 個字元，且須包含至少一個大寫字母與一個數字。',
        '2fa_help_text' => [
            '此指令會停用該使用者帳號已啟用的雙重驗證。此指令應僅在使用者無法登入帳號時，作為帳號復原用途使用。',
            '若這不是你想做的事，請按下 CTRL+C 結束此程序。',
        ],
        '2fa_disabled' => '已為 :email 停用雙重驗證。',
    ],
    'schedule' => [
        'output_line' => '正在為排程 `:schedule`（:hash）的第一項任務派發工作。',
    ],
    'maintenance' => [
        'deleting_service_backup' => '正在刪除服務備份檔案 :file。',
    ],
    'server' => [
        'rebuild_failed' => '節點「:node」上「:name」（#:id）的重建請求失敗，錯誤訊息：:message',
        'reinstall' => [
            'failed' => '節點「:node」上「:name」（#:id）的重新安裝請求失敗，錯誤訊息：:message',
            'confirm' => '你即將對一批伺服器執行重新安裝。是否要繼續？',
        ],
        'power' => [
            'confirm' => '你即將對 :count 台伺服器執行 :action 操作。是否要繼續？',
            'action_failed' => '節點「:node」上「:name」（#:id）的電源操作請求失敗，錯誤訊息：:message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'SMTP 主機（例如 smtp.gmail.com）',
            'ask_smtp_port' => 'SMTP 連接埠',
            'ask_smtp_username' => 'SMTP 使用者名稱',
            'ask_smtp_password' => 'SMTP 密碼',
            'ask_mailgun_domain' => 'Mailgun 網域',
            'ask_mailgun_endpoint' => 'Mailgun 端點',
            'ask_mailgun_secret' => 'Mailgun 金鑰',
            'ask_mandrill_secret' => 'Mandrill 金鑰',
            'ask_postmark_username' => 'Postmark API 金鑰',
            'ask_driver' => '要使用哪個驅動程式來寄送電子郵件？',
            'ask_mail_from' => '郵件應顯示的寄件者電子郵件地址',
            'ask_mail_name' => '郵件應顯示的寄件者名稱',
            'ask_encryption' => '要使用的加密方式',
        ],
    ],
];
