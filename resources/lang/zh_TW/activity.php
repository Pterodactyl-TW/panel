<?php

/**
 * 這裡包含了各種活動紀錄事件的翻譯字串。
 * 這些字串應以事件名稱中冒號（:）前面的值作為 key；
 * 若事件名稱中沒有冒號，則應放在最上層。
 */
return [
    'auth' => [
        'fail' => '登入失敗',
        'success' => '已登入',
        'password-reset' => '已重設密碼',
        'reset-password' => '已請求重設密碼',
        'checkpoint' => '已請求雙重驗證',
        'recovery-token' => '已使用雙重驗證復原權杖',
        'token' => '已完成雙重驗證挑戰',
        'ip-blocked' => '已封鎖來自不在允許清單中 :identifier 的請求',
        'sftp' => [
            'fail' => 'SFTP 登入失敗',
        ],
    ],
    'user' => [
        'user' => [
            'create' => '建立了新使用者 :email',
        ],
        'account' => [
            'email-changed' => '將電子郵件從 :old 變更為 :new',
            'password-changed' => '已變更密碼',
        ],
        'api-key' => [
            'create' => '建立了新的 API 金鑰 :identifier',
            'delete' => '刪除了 API 金鑰 :identifier',
        ],
        'ssh-key' => [
            'create' => '將 SSH 金鑰 :fingerprint 新增至帳號',
            'delete' => '從帳號中移除了 SSH 金鑰 :fingerprint',
        ],
        'two-factor' => [
            'create' => '已啟用雙重驗證',
            'delete' => '已停用雙重驗證',
        ],
    ],
    'server' => [
        'reinstall' => '重新安裝了伺服器',
        'console' => [
            'command' => '在伺服器上執行了「:command」',
        ],
        'power' => [
            'start' => '啟動了伺服器',
            'stop' => '停止了伺服器',
            'restart' => '重新啟動了伺服器',
            'kill' => '強制終止了伺服器程序',
        ],
        'backup' => [
            'download' => '下載了 :name 備份',
            'delete' => '刪除了 :name 備份',
            'restore' => '還原了 :name 備份（刪除的檔案：:truncate）',
            'restore-complete' => '完成了 :name 備份的還原',
            'restore-failed' => ':name 備份的還原失敗',
            'start' => '開始了新的備份 :name',
            'complete' => '將 :name 備份標記為已完成',
            'fail' => '將 :name 備份標記為失敗',
            'lock' => '鎖定了 :name 備份',
            'unlock' => '解除鎖定了 :name 備份',
        ],
        'database' => [
            'create' => '建立了新資料庫 :name',
            'rotate-password' => '為資料庫 :name 重新產生了密碼',
            'delete' => '刪除了資料庫 :name',
        ],
        'file' => [
            'compress_one' => '壓縮了 :directory:files.0',
            'compress_other' => '在 :directory 中壓縮了 :count 個檔案',
            'read' => '檢視了 :file 的內容',
            'copy' => '建立了 :file 的副本',
            'create-directory' => '建立了目錄 :directory:name',
            'decompress' => '在 :directory 中解壓縮了 :files',
            'delete_one' => '刪除了 :directory:files.0',
            'delete_other' => '在 :directory 中刪除了 :count 個檔案',
            'download' => '下載了 :file',
            'pull' => '從 :url 下載了遠端檔案至 :directory',
            'rename_one' => '將 :directory:files.0.from 重新命名為 :directory:files.0.to',
            'rename_other' => '在 :directory 中重新命名了 :count 個檔案',
            'write' => '將新內容寫入 :file',
            'upload' => '開始了檔案上傳',
            'uploaded' => '上傳了 :directory:file',
        ],
        'sftp' => [
            'denied' => '因權限不足而封鎖了 SFTP 存取',
            'create_one' => '建立了 :files.0',
            'create_other' => '建立了 :count 個新檔案',
            'write_one' => '修改了 :files.0 的內容',
            'write_other' => '修改了 :count 個檔案的內容',
            'delete_one' => '刪除了 :files.0',
            'delete_other' => '刪除了 :count 個檔案',
            'create-directory_one' => '建立了 :files.0 目錄',
            'create-directory_other' => '建立了 :count 個目錄',
            'rename_one' => '將 :files.0.from 重新命名為 :files.0.to',
            'rename_other' => '重新命名或移動了 :count 個檔案',
        ],
        'allocation' => [
            'create' => '將 :allocation 新增至伺服器',
            'notes' => '將 :allocation 的備註從「:old」更新為「:new」',
            'primary' => '將 :allocation 設為伺服器的主要配置',
            'delete' => '刪除了配置 :allocation',
        ],
        'schedule' => [
            'create' => '建立了排程 :name',
            'update' => '更新了排程 :name',
            'execute' => '手動執行了排程 :name',
            'delete' => '刪除了排程 :name',
        ],
        'task' => [
            'create' => '為排程 :name 建立了新的「:action」任務',
            'update' => '更新了排程 :name 的「:action」任務',
            'delete' => '刪除了排程 :name 的一項任務',
        ],
        'settings' => [
            'rename' => '將伺服器名稱從 :old 重新命名為 :new',
            'description' => '將伺服器描述從 :old 變更為 :new',
        ],
        'startup' => [
            'edit' => '將變數 :variable 從「:old」變更為「:new」',
            'image' => '將伺服器的 Docker 映像檔從 :old 更新為 :new',
        ],
        'subuser' => [
            'create' => '將 :email 新增為子使用者',
            'update' => '更新了子使用者 :email 的權限',
            'delete' => '移除了子使用者 :email',
        ],
    ],
];
