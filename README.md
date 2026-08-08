[![Logo Image](https://cdn.pterodactyl.io/logos/new/pterodactyl_logo.png)](https://pterodactyl.tw)

![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/Pterodactyl-TW/panel/ci.yaml?label=Tests&style=for-the-badge&branch=1.0-develop)
![Discord](https://img.shields.io/discord/1534664674740535367?label=Discord&logo=Discord&logoColor=white&style=for-the-badge)
![GitHub Releases](https://img.shields.io/github/downloads/Pterodactyl-TW/panel/latest/total?style=for-the-badge)
![GitHub contributors](https://img.shields.io/github/contributors/Pterodactyl-TW/panel?style=for-the-badge)

# Pterodactyl Panel（繁體中文化版本）

> [!NOTE]
> 本倉庫由 **Pterodactyl 台灣翻譯團隊** 維護，提供 Pterodactyl 官方 Panel 的繁體中文化版本。若你發現翻譯有誤或需要更新的地方，歡迎直接開 Pull Request，或透過 [Discord](https://pterodactyl.tw/discord) 與我們聯繫。

Pterodactyl® 是一套使用 PHP、React 與 Go 打造的免費開源遊戲伺服器管理面板。Pterodactyl 以安全性為設計核心，
所有遊戲伺服器都在隔離的 Docker 容器中執行，同時提供終端使用者美觀又直覺的操作介面。

別再將就了，讓遊戲伺服器成為你平台上的一等公民。

![Image](https://cdn.pterodactyl.io/site-assets/pterodactyl_v1_demo.gif)

## Demo 模式

本分支提供選用的瀏覽器端模擬後端，適合架設公開展示站。只要在任一面板網址加上 `?demo=1`
（例如 `https://panel.example.test/auth/login?demo=1`），前端便會註冊 `/demo-service-worker.js` 並重新載入，
接著提供虛構的 Client API 資料與模擬的終端 WebSocket。未實作的 Client API 會以 HTTP 501 封閉失敗，
絕不轉送至真實後端。可按黃色提示列中的 **離開 Demo**，或使用 `?demo=0` 取消註冊 Demo Worker。

Service Worker 需要 HTTPS（或 `localhost`）。Demo 中的變更僅保存在記憶體，Worker 重啟後便會重設。

```bash
yarn test:demo-worker
yarn test resources/scripts/demo/bootstrap.spec.ts resources/scripts/plugins/DemoWebsocket.spec.ts
```

## 文件

* [Panel 文件](https://pterodactyl.tw/panel/1.0/getting_started.html)
* [Wings 文件](https://pterodactyl.tw/wings/1.0/installing.html)
* [社群指南](https://pterodactyl.tw/community/about.html)
* 或透過 [Discord](https://pterodactyl.tw/discord) 尋求協助

## 贊助商

在此由衷感謝以下贊助商協助資助 Pterodactyl 的開發。
[有興趣成為贊助商嗎？](https://github.com/sponsors/pterodactyl)

| 公司                                                                                 | 介紹                                                                                                                                                                                                                                            |
|-------------------------------------------------------------------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| [**Aussie Server Hosts**](https://aussieserverhosts.com/)                         | 澳洲人自有自營、不搞花俏噱頭的高效能伺服器代管服務，為澳洲與紐西蘭提供部分最吃資源的遊戲伺服器。                                                                                                                                                                 |
| [**BisectHosting**](https://www.bisecthosting.com/)                               | BisectHosting 自 2012 年起提供 Minecraft、Valheim 及其他伺服器代管服務，以高可靠度與極速支援著稱。                                                                                                                                                                 |
| [**MineStrator**](https://minestrator.com/)                                       | 想找最頂級的法國代管公司來架設你的 Minecraft 伺服器嗎？我們的 Discord 已有超過 24,000 名成員信賴我們，歡迎你也來試試看！                                                                                                                                                                 |
| [**HostEZ**](https://hostez.io)                                                   | 美國與歐洲的 Rust 與 Minecraft 代管服務。提供具備 DDoS 防護的裸機、VPS 與機房代管服務，低延遲、高可用性，簡單又輕鬆！                                                                                                                                                                     |
| [**Blueprint**](https://blueprint.zip/?utm_source=pterodactyl&utm_medium=sponsor) | 使用日益成長的 Blueprint 框架來建立並安裝 Pterodactyl 附加元件與佈景主題，這是 Pterodactyl 的套件管理工具。可同時使用多項修改而不必擔心衝突，並善用龐大的擴充生態系。 |
| [**indifferent broccoli**](https://indifferentbroccoli.com/)                      | indifferent broccoli 是一間遊戲伺服器代管與租賃公司。在我們這裡，你能為遊戲時光取得頂級的運算效能。我們消滅延遲、掉線與複雜性，讓你能專心享受遊戲樂趣。                              |

### 支援的遊戲

Pterodactyl 藉由使用 Docker 容器隔離每個實例，支援種類繁多的遊戲。這讓你能在不讓主機塞滿一堆額外相依套件的情況下執行遊戲伺服器。

我們核心支援的部分遊戲包括：

* Minecraft（包括 Paper、Sponge、Bungeecord、Waterfall 等）
* Rust
* Terraria
* Teamspeak
* Mumble
* Team Fortress 2
* Counter Strike: Global Offensive
* Garry's Mod
* ARK: Survival Evolved

除了我們標準內建的遊戲支援之外，我們的社群也持續不斷地挑戰這套軟體的極限，還有更多由社群提供的遊戲可供使用。這些遊戲包括：

* Factorio
* San Andreas: MP
* Pocketmine MP
* Squad
* Xonotic
* Starmade
* Discord ATLBot，以及大多數其他的 Node.js/Python Discord 機器人
* [還有更多……](https://eggs.pterodactyl.tw)

## 繁體中文化服務團隊

<table>
  <tr>
    <td align="center"><a href="https://github.com/AvianJay"><img src="https://github.com/AvianJay.png" width="80px;" alt="AvianJay"/><br /><sub><b>AvianJay</b></sub></a></td>
    <td align="center"><a href="https://github.com/creeperdevme"><img src="https://github.com/creeperdevme.png" width="80px;" alt="creeperdevme"/><br /><sub><b>creeperdevme</b></sub></a></td>
    <td align="center"><a href="https://github.com/Kevin28576"><img src="https://github.com/Kevin28576.png" width="80px;" alt="Kevin28576"/><br /><sub><b>Kevin28576</b></sub></a></td>
    <td align="center"><a href="https://github.com/kusanagi-akane"><img src="https://github.com/kusanagi-akane.png" width="80px;" alt="kusanagi-akane"/><br /><sub><b>kusanagi-akane</b></sub></a></td>
    <td align="center"><a href="https://github.com/littlecommandcat"><img src="https://github.com/littlecommandcat.png" width="80px;" alt="littlecommandcat"/><br /><sub><b>littlecommandcat</b></sub></a></td>
    <td align="center"><a href="https://github.com/rise0313"><img src="https://github.com/rise0313.png" width="80px;" alt="rise0313"/><br /><sub><b>rise0313</b></sub></a></td>
  </tr>
</table>

如果你也想加入我們的繁體中文化服務團隊，歡迎透過 [Discord](https://pterodactyl.tw/discord) 與我們聯絡。

## 授權

Pterodactyl® Copyright © 2015 - 2022 Dane Everitt 與貢獻者。
繁體中文化版本 Copyright © Pterodactyl 台灣翻譯團隊。

程式碼採用 [MIT 授權條款](./LICENSE.md) 釋出。
