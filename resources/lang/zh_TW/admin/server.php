<?php

return [
    'exceptions' => [
        'no_new_default_allocation' => '你正嘗試刪除此伺服器的預設配置，但目前沒有可供替代的配置。',
        'marked_as_failed' => '此伺服器已被標記為先前的安裝失敗。目前狀態無法在此狀態下切換。',
        'bad_variable' => '變數 :name 發生驗證錯誤。',
        'daemon_exception' => '嘗試與 daemon 通訊時發生例外，收到 HTTP/:code 的回應狀態碼。此例外已被記錄。（請求 ID：:request_id）',
        'default_allocation_not_found' => '在此伺服器的配置中找不到請求的預設配置。',
    ],
    'alerts' => [
        'startup_changed' => '此伺服器的啟動組態設定已更新。若此伺服器的 nest 或 egg 有變更，現在將會進行重新安裝。',
        'server_deleted' => '已成功從系統中刪除伺服器。',
        'server_created' => '已成功在 Panel 上建立伺服器。請給予 daemon 幾分鐘的時間完整安裝此伺服器。',
        'build_updated' => '此伺服器的建置詳細資訊已更新。部分變更可能需要重新啟動才能生效。',
        'suspension_toggled' => '伺服器停權狀態已變更為 :status。',
        'rebuild_on_boot' => '此伺服器已被標記為需要重建 Docker 容器，將於下次啟動伺服器時進行。',
        'install_toggled' => '此伺服器的安裝狀態已切換。',
        'server_reinstalled' => '此伺服器已排入佇列，即將開始重新安裝。',
        'details_updated' => '伺服器詳細資訊已成功更新。',
        'docker_image_updated' => '已成功變更此伺服器使用的預設 Docker 映像檔。需要重新啟動才能套用此變更。',
        'node_required' => '在新增伺服器之前，你必須先設定至少一個節點。',
        'transfer_nodes_required' => '在轉移伺服器之前，你必須先設定至少兩個節點。',
        'transfer_started' => '伺服器轉移已開始。',
        'transfer_not_viable' => '你選擇的節點沒有足夠的硬碟空間或記憶體來容納此伺服器。',
    ],
];
