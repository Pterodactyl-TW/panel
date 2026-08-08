<?php

return [
    'notices' => [
        'created' => '已成功建立新的 nest：:name。',
        'deleted' => '已成功從 Panel 刪除該 nest。',
        'updated' => '已成功更新 nest 的組態設定選項。',
    ],
    'eggs' => [
        'notices' => [
            'imported' => '已成功匯入此 Egg 及其相關變數。',
            'updated_via_import' => '已使用提供的檔案更新此 Egg。',
            'deleted' => '已成功從 Panel 刪除該 egg。',
            'updated' => 'Egg 組態設定已成功更新。',
            'script_updated' => 'Egg 安裝腳本已更新，將於伺服器安裝時執行。',
            'egg_created' => '已成功孵化出新的 egg。你需要重新啟動任何執行中的 daemon 以套用這個新的 egg。',
        ],
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => '變數「:variable」已被刪除，伺服器重建後將無法再使用此變數。',
            'variable_updated' => '變數「:variable」已更新。你需要重建任何使用此變數的伺服器以套用變更。',
            'variable_created' => '已成功建立新變數並指派給此 egg。',
        ],
    ],
];
