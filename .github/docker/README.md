# Pterodactyl Panel - Docker 映像檔
這是一個可直接使用的 Panel Docker 映像檔。

## 需求
此 Docker 映像檔需要一些額外的軟體才能運作。這些軟體可以透過其他容器提供（範例請見 [docker-compose.yml](https://github.com/pterodactyl/panel/blob/develop/docker-compose.example.yml)），也可以使用既有的實例。

需要一個 MySQL 資料庫。若你偏好在 Docker 容器中執行，我們建議使用官方的 [MariaDB 映像檔](https://hub.docker.com/_/mariadb/)。若不使用容器化方式，我們建議使用 mariadb。

同時也需要一套快取軟體。我們建議使用官方的 [Redis 映像檔](https://hub.docker.com/_/redis/)。你也可以選擇任何[受支援的選項](#cache-drivers)。

你可以使用自訂的 `.env` 檔案，或在 docker-compose 檔案中設定對應的環境變數，來提供額外的設定。

## 設定

啟動 Docker 容器與所需的相依服務（可自行提供既有服務，或一併啟動容器，範例請見 [docker-compose.yml](https://github.com/pterodactyl/panel/blob/develop/docker-compose.example.yml) 檔案）。

啟動完成後，你需要建立一個使用者。
若你是在不使用 docker-compose 的情況下執行 Docker 容器，請使用：
```
docker exec -it <container id> php artisan p:user:make
```
若你使用 docker compose，請使用
```
docker compose exec panel php artisan p:user:make
```

## 環境變數
若不提供自己的 `.env` 檔案，可透過多個環境變數來設定 Panel，各可用選項的詳細說明請參見下表。

註：若你的 `APP_URL` 是以 `https://` 開頭，你也需要提供 `LE_EMAIL`，才能產生憑證。

| 變數                 | 說明                                                                    | 是否必要 |
| ------------------- | ------------------------------------------------------------------------------ | -------- |
| `APP_URL`           | Panel 對外連線所使用的網址（含通訊協定）                  | 是      |
| `APP_TIMEZONE`      | Panel 使用的時區                                              | 是      |
| `LE_EMAIL`          | 用於產生 Let's Encrypt 憑證的電子郵件                          | 是      |
| `DB_HOST`           | MySQL 實例的主機位址                                                 | 是      |
| `DB_PORT`           | MySQL 實例的連接埠                                                 | 是      |
| `DB_DATABASE`       | MySQL 資料庫名稱                                                 | 是      |
| `DB_USERNAME`       | MySQL 使用者                                                                 | 是      |
| `DB_PASSWORD`       | 指定使用者對應的 MySQL 密碼                                      | 是      |
| `CACHE_DRIVER`      | 快取驅動程式（詳見 [快取驅動程式](#cache-drivers)）       | 是      |
| `SESSION_DRIVER`    |                                                                                | 是      |
| `QUEUE_DRIVER`      |                                                                                | 是      |
| `REDIS_HOST`        | Redis 資料庫的主機名稱或 IP 位址                               | 是      |
| `REDIS_PASSWORD`    | 用於保護 Redis 資料庫安全的密碼                                 | 視情況    |
| `REDIS_PORT`        | Redis 資料庫在主機上使用的連接埠                               | 視情況    |
| `MAIL_DRIVER`       | 電子郵件驅動程式（詳見 [郵件驅動程式](#mail-drivers)）               | 是      |
| `MAIL_FROM`         | 作為寄件者的電子郵件地址                              | 是      |
| `MAIL_HOST`         | 你的郵件驅動程式實例主機                                          | 視情況    |
| `MAIL_PORT`         | 你的郵件驅動程式實例連接埠                                          | 視情況    |
| `MAIL_USERNAME`     | 你的郵件驅動程式使用者名稱                                              | 視情況    |
| `MAIL_PASSWORD`     | 你的郵件驅動程式密碼                                              | 視情況    |


### 快取驅動程式
你可依照喜好在不同的快取驅動程式之間做選擇。
在使用 Docker 時，我們建議使用 redis，因為它可以輕鬆地在容器中啟動。

| 驅動程式   | 說明                          | 必要變數                                     |
| -------- | ------------------------------------ | ------------------------------------------------------ |
| redis    | Redis 執行所在的主機                          | `REDIS_HOST`                                           |
| redis    | Redis 執行所使用的連接埠                             | `REDIS_PORT`                                           |
| redis    | Redis 資料庫密碼                              | `REDIS_PASSWORD`                                       |

### 郵件驅動程式
你可依照需求在不同的郵件驅動程式之間做選擇。
每種驅動程式都必須設定 `MAIL_FROM`。

| 驅動程式   | 說明                          | 必要變數                                            |
| -------- | ------------------------------------ | ------------------------------------------------------------- |
| mail     | 使用已安裝的 php mail 功能          |                                                               |
| mandrill | [Mandrill](http://www.mandrill.com/) | `MAIL_USERNAME`                                               |
| postmark | [Postmark](https://postmarkapp.com/) | `MAIL_USERNAME`                                               |
| mailgun  | [Mailgun](https://www.mailgun.com/)  | `MAIL_USERNAME`, `MAIL_HOST`                                  |
| smtp     | 可設定任何 SMTP 伺服器    | `MAIL_USERNAME`, `MAIL_HOST`, `MAIL_PASSWORD`, `MAIL_PORT`    |
