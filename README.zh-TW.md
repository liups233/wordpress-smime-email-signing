# WordPress S/MIME 郵件簽署外掛

[English](https://github.com/liups233/wordpress-smime-email-signing/blob/main/README.md) | [简体中文](https://github.com/liups233/wordpress-smime-email-signing/blob/main/README.zh-CN.md) | 繁體中文

用於將所有外發郵件使用 S/MIME 證書簽署。

你好，這是我做的第一個 WordPress 外掛，感謝使用。

我的部落格：[Liups233の小站](https://www.liups.net)

## 關於 S/MIME
S/MIME 是一種用於加密、解密或簽署電子郵件的協議。本外掛專注於其簽署功能。

電子郵件簽章可以告知收件人您的電子郵件未被他人篡改，並且他們可以信任這些電子郵件由您發送。

您只需獲取一個 S/MIME 證書，完成以下步驟，所有外發電子郵件將自動簽署。

## 先決條件
- 本外掛適用於單用戶 WordPress 網站，例如個人部落格。多用戶網站上，可能正常運行。
- 建議使用 PHP 8.3 及以上版本。請確保您的 PHP 環境已安裝 `openssl` 擴展（通常默認安裝）。
- 您需要使用其他外掛（如 FluentSMTP）通過外部 SMTP 伺服器發送郵件。

## 使用方法
### 獲取 S/MIME 證書
您可以訪問 Actalis 申請為期一年的免費 S/MIME 證書。

申請後，您可以下載包含公鑰、私鑰和證書鏈的 `p12` 證書檔案。

`p12` 檔案的密碼將發送至您的信箱。

### 拆分 p12 證書
假設 p12 檔案名為 “smime.p12”，請執行以下命令將其分割為獨立的金鑰。
- 導出私鑰：`openssl pkcs12 -in smime.p12 -nocerts -out smime.key`
  - 系統會提示輸入導入密碼，即 p12 檔案的密碼。
  - 隨後，您需要輸入一個密碼來保護導出的私鑰。此密碼即為外掛設置中需要填寫的密碼。
  - 輸入兩次以確認。
- 導出公鑰：`openssl pkcs12 -in smime.p12 -clcerts -nokeys -out smime.crt`
  - 系統將提示輸入導入密碼，即 p12 檔案的密碼。
- 導出證書鏈：`openssl pkcs12 -in smime.p12 -cacerts -nokeys -out certchain.pem`
  - 系統將提示輸入導入密碼，即 p12 檔案的密碼。
  
### 設置權限
上傳金鑰檔案到伺服器後，需要為每個檔案設定權限。
- 私鑰：`chmod 640 smime.key`
- 公鑰：`chmod 644 smime.crt`
- 證書鏈：`chmod 644 certchain.pem`

### 外掛設置
安裝並啟用外掛後，設置頁面將出現在左側，並帶有盾牌圖示。

您需要填寫路徑和密碼欄位，點擊保存，即可完成設置。

您可以使用 SMTP 外掛中的「發送測試郵件」功能進行測試。

卸載外掛後，設置將自動刪除。
