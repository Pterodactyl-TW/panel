<?php

return [
    // 舊的活動紀錄項目在建立幾天後會被刪除。
    'prune_days' => env('APP_ACTIVITY_PRUNE_DAYS', 90),

    // 若設為 true，由管理員使用者產生、但該管理員並非該伺服器成員的活動紀錄項目，
    // 將會從活動紀錄 API 回應中隱藏。
    //
    // 這些活動仍會被正常記錄，只是不會顯示出來。
    'hide_admin_activity' => env('APP_ACTIVITY_HIDE_ADMIN', false),
];
