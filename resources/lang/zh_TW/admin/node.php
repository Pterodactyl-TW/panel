<?php

return [
    'validation' => [
        'fqdn_not_resolvable' => '提供的 FQDN 或 IP 位址無法解析為有效的 IP 位址。',
        'fqdn_required_for_ssl' => '若要在此節點使用 SSL，需要提供可解析為公開 IP 位址的完整網域名稱（FQDN）。',
    ],
    'notices' => [
        'allocations_added' => '已成功將配置新增至此節點。',
        'node_deleted' => '已成功從 Panel 移除節點。',
        'location_required' => '在新增節點之前，你必須先設定至少一個位置。',
        'node_created' => '已成功建立新節點。你可以前往「組態設定」分頁自動設定此機器上的 daemon。在新增任何伺服器之前，你必須先配置至少一組 IP 位址與連接埠。',
        'node_updated' => '節點資訊已更新。若有任何 daemon 設定被變更，你需要重新啟動它才能套用這些變更。',
        'unallocated_deleted' => '已刪除 <code>:ip</code> 所有未配置的連接埠。',
    ],
];
