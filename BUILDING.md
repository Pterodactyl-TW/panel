# 本機開發
Pterodactyl 現在以 React、Typescript 與 Tailwindcss 為核心，並使用 webpack 產生編譯後的資源檔案。
Pterodactyl 的正式發行版本會附上已預先編譯、壓縮並加上雜湊值的資源檔案，可直接使用。

不過，若你想使用自訂佈景主題，或修改 React 檔案，就需要一套建置系統來產生這些編譯後的資源。要設定好你的環境，至少需要：

* [Node.js](https://nodejs.org/en/) v14.x.x
* [Yarn](https://classic.yarnpkg.com/lang/en/) v1.x.x
* [Go](https://golang.org/) 1.17.x

### 安裝相依套件
```bash
yarn install
```

上述指令會下載建置 Pterodactyl 資源所需的所有相依套件。之後，只需執行下方指令，即可在開發過程中產生資源檔案。
在你至少執行過一次這個指令之前，你的 Panel 可能會因為缺少 `manifest.json` 檔案而顯示 500 錯誤。這個檔案會由下方的指令產生。

```bash
# 建置開發用的編譯資源集合。
yarn run build

# 當檔案變更時自動建置資源。這樣一來，
# 你只需重新整理頁面即可立即看到變更。
yarn run watch
```

### 熱模組替換（Hot Module Reloading）
對於較進階的使用者，我們也支援「熱模組替換」，讓你在修改 Vue 樣板檔案時可以快速看到變更，而不需要重新載入頁面。要開始使用此功能，只需執行下方指令。

```bash
PUBLIC_PATH=http://192.168.1.1:8080 yarn run serve --host 192.168.1.1
```

這道指令有兩個**非常重要**的部分，請務必依你的環境調整。第一個是 `--host` 選項，這是必要參數，應指向執行 `webpack-serve` 伺服器的機器。
第二個是 `PUBLIC_PATH` 環境變數，這是指向 HMR 伺服器的網址，會附加在 Pterodactyl 所使用的所有資源網址後方。

#### 開發環境
若你使用的是 [`pterodactyl/development`](https://github.com/pterodactyl/development) 環境（強烈建議使用），只需執行 `yarn run serve` 即可啟動 HMR 伺服器，不需要額外的設定。

### 建置正式環境版本
當你把檔案都準備好，可以推送到正式伺服器時，就需要產生已編譯、壓縮並加上雜湊值的資源。請執行下方指令：

```bash
yarn run build:production
```

這會產生正式環境用的 JS 打包檔與相關資源，全部放在 `public/assets/` 目錄下，你需要將其上傳至你的伺服器或 CDN 供客戶端使用。

### 執行 Wings
若要在開發環境中執行 `Wings`，只需依照新增節點時的一般流程設定好組態設定檔即可，接著在 Wings 程式碼目錄下執行 `make debug`，就能建置並執行本機版本的 Wings。此指令必須在某種 Linux 虛擬機上執行，無法直接在 macOS 或 Windows 上執行。
