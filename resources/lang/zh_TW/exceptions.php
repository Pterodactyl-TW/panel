<?php

return [
    'daemon_connection_failed' => '嘗試與 Wings 通訊時發生例外，收到 HTTP/:code 的回應狀態碼。此例外已被記錄。',
    'node' => [
        'servers_attached' => '節點必須沒有任何伺服器連結才能被刪除。',
        'daemon_off_config_updated' => 'Wings 組態設定已更新，但嘗試自動更新 Wings 上的組態設定檔時發生錯誤。你需要手動更新 Daemon 的組態設定檔（config.yml）以套用這些變更。',
    ],
    'allocations' => [
        'server_using' => '目前有伺服器指派到此配置。只有在沒有伺服器使用此配置時才能刪除。',
        'too_many_ports' => '不支援在單一範圍內一次新增超過 1000 個連接埠。',
        'invalid_mapping' => '為 :port 提供的對應無效，無法處理。',
        'cidr_out_of_range' => 'CIDR 表示法僅允許 /25 到 /32 之間的遮罩。',
        'port_out_of_range' => '配置中的連接埠必須大於 1024 且小於或等於 65535。',
    ],
    'nest' => [
        'delete_has_servers' => '無法從 Panel 刪除仍有伺服器使用的 Nest。',
        'egg' => [
            'delete_has_servers' => '無法從 Panel 刪除仍有伺服器使用的 Egg。',
            'invalid_copy_id' => '選擇要複製腳本來源的 Egg 不存在，或該 Egg 本身也是複製而來。',
            'must_be_child' => '此 Egg 的「從其他項目複製設定」指令必須是所選 Nest 下的子選項。',
            'has_children' => '此 Egg 是其他一個或多個 Egg 的父項。請先刪除那些 Egg，再刪除這個 Egg。',
        ],
        'variables' => [
            'env_not_unique' => '環境變數 :name 在此 Egg 中必須是唯一的。',
            'reserved_name' => '環境變數 :name 已受保護，無法指派給變數使用。',
            'bad_validation_rule' => '驗證規則「:rule」對此應用程式來說不是有效的規則。',
        ],
        'importer' => [
            'json_error' => '嘗試解析 JSON 檔案時發生錯誤：:error。',
            'file_error' => '提供的 JSON 檔案無效。',
            'invalid_json_provided' => '提供的 JSON 檔案格式無法被辨識。',
        ],
    ],
    'subusers' => [
        'editing_self' => '不允許編輯自己的子使用者帳號。',
        'user_is_owner' => '無法將此伺服器的擁有者新增為子使用者。',
        'subuser_exists' => '已有使用該電子郵件地址的使用者被指派為此伺服器的子使用者。',
    ],
    'databases' => [
        'delete_has_databases' => '無法刪除仍有資料庫連結的資料庫主機伺服器。',
    ],
    'tasks' => [
        'chain_interval_too_long' => '連鎖任務的最長間隔時間為 15 分鐘。',
    ],
    'locations' => [
        'has_nodes' => '無法刪除仍有節點連結的位置。',
    ],
    'users' => [
        'node_revocation_failed' => '無法撤銷 <a href=":link">節點 #:node</a> 上的金鑰。:error',
    ],
    'deployment' => [
        'no_viable_nodes' => '找不到符合自動部署所需條件的節點。',
        'no_viable_allocations' => '找不到符合自動部署所需條件的配置。',
    ],
    'api' => [
        'resource_not_found' => '此伺服器上不存在請求的資源。',
    ],
];
