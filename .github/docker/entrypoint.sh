#!/bin/ash -e
cd /app

mkdir -p /var/log/panel/logs/ /var/log/supervisord/ /var/log/nginx/ /var/log/php7/ \
  && chmod 777 /var/log/panel/logs/ \
  && ln -s /app/storage/logs/ /var/log/panel/

## 檢查是否有 .env 檔案，若缺少則產生應用程式金鑰
if [ -f /app/var/.env ]; then
  echo "外部變數已存在。"
  rm -rf /app/.env
  ln -s /app/var/.env /app/
else
  echo "外部變數不存在。"
  rm -rf /app/.env
  touch /app/var/.env

  ## 手動產生金鑰，因為 key generate --force 會失敗
  if [ -z $APP_KEY ]; then
     echo -e "正在產生金鑰。"
     APP_KEY=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 32 | head -n 1)
     echo -e "已產生應用程式金鑰：$APP_KEY"
     echo -e "APP_KEY=$APP_KEY" > /app/var/.env
  else
    echo -e "環境變數中已存在 APP_KEY，將使用該值。"
    echo -e "APP_KEY=$APP_KEY" > /app/var/.env
  fi

  ## 若未提供，則為 hashids 產生隨機的 salt
  if [ -z $HASHIDS_SALT ]; then
     echo -e "正在產生 hashids salt。"
     HASHIDS_SALT=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9!@#$%^&*()_+?><~' | fold -w 20 | head -n 1)
     echo -e "已產生 hashids salt：$HASHIDS_SALT"
     echo -e "HASHIDS_SALT=$HASHIDS_SALT" >> /app/var/.env
  else
    echo -e "環境變數中已存在 HASHIDS_SALT，將使用該值。"
    echo -e "HASHIDS_SALT=$HASHIDS_SALT" >> /app/var/.env
  fi

  ln -s /app/var/.env /app/
fi

echo "正在檢查是否需要 https。"
if [ -f /etc/nginx/http.d/panel.conf ]; then
  echo "使用既有的 nginx 組態設定。"
  if [ $LE_EMAIL ]; then
    echo "正在檢查憑證更新"
    certbot certonly -d $(echo $APP_URL | sed 's~http[s]*://~~g')  --standalone -m $LE_EMAIL --agree-tos -n
  else
    echo "未設定 letsencrypt 電子郵件"
  fi
else
  echo "正在檢查是否設定了 letsencrypt 電子郵件。"
  if [ -z $LE_EMAIL ]; then
    echo "未設定 letsencrypt 電子郵件，使用 http 組態設定。"
    cp .github/docker/default.conf /etc/nginx/http.d/panel.conf
  else
    echo "正在寫入 ssl 組態設定"
    cp .github/docker/default_ssl.conf /etc/nginx/http.d/panel.conf
    echo "正在為網域更新 ssl 組態設定"
    sed -i "s|<domain>|$(echo $APP_URL | sed 's~http[s]*://~~g')|g" /etc/nginx/http.d/panel.conf
    echo "正在產生憑證"
    certbot certonly -d $(echo $APP_URL | sed 's~http[s]*://~~g')  --standalone -m $LE_EMAIL --agree-tos -n
  fi
  echo "正在移除預設的 nginx 組態設定"
  rm -rf /etc/nginx/http.d/default.conf
fi

if [[ -z $DB_PORT ]]; then
  echo -e "未指定 DB_PORT，預設使用 3306"
  DB_PORT=3306
fi

## 檢查日誌資料夾權限
echo "正在檢查日誌資料夾權限。"
if [ "$(stat -c %U:%G /app/storage/logs)" != "nginx" ]; then
  echo "正在修正日誌資料夾權限。"
  chown -R nginx: /app/storage/logs/
fi

## 在啟動 Panel 前檢查資料庫是否已就緒
echo "正在檢查資料庫狀態。"
until nc -z -v -w30 $DB_HOST $DB_PORT
do
  echo "正在等待資料庫連線..."
  # 等待 1 秒後再次檢查
  sleep 1
done

## 確保資料庫已設定完成
echo -e "正在遷移並填入資料庫種子資料"
php artisan migrate --seed --force

## 啟動佇列所需的排程工作
echo -e "正在啟動排程工作。"
crond -L /var/log/crond -l 5

echo -e "正在啟動 supervisord。"
exec "$@"
