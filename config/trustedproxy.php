<?php

return [
    /*
     * 設定信任的 Proxy IP 位址。
     *
     * 同時支援 IPv4 與 IPv6 位址，
     * 也支援 CIDR 表示法。
     *
     * 「*」字元是 TrustedProxy 提供的語法糖，
     * 代表信任任何直接連線到你伺服器的 proxy，
     * 適用於你無法得知 proxy 位址的情況
     * （例如使用 Rackspace 負載平衡器時）。
     *
     * 「**」字元是 TrustedProxy 提供的語法糖，
     * 不只信任直接連線到你伺服器的 proxy，
     * 也信任連線到那些 proxy 的其他 proxy，
     * 一路回溯直到找到最原始的來源 IP。
     * 這代表 $request->getClientIp() 一律能取得
     * 最原始的客戶端 IP，不論該客戶端的請求
     * 實際上經過了多少層 proxy 轉發。
     */
    'proxies' => in_array(env('TRUSTED_PROXIES', []), ['*', '**']) ?
        env('TRUSTED_PROXIES') : explode(',', env('TRUSTED_PROXIES') ?? ''),
];
