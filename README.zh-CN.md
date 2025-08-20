# WordPress S/MIME 邮件签名插件

[English](https://github.com/liups233/wordpress-smime-email-signing/blob/main/README.md) | 简体中文 | [繁體中文](https://github.com/liups233/wordpress-smime-email-signing/blob/main/README.zh-TW.md)

用于将所有外发邮件使用 S/MIME 证书签名。

你好，这是我做的第一个 WordPress 插件，感谢使用。

我的博客：[Liups233の小站](https://www.liups.net)

## 关于 S/MIME
S/MIME 是一种用于加密、解密或签名电子邮件的协议。本插件专注于其签名功能。

电子邮件签名可以告知收件人您的电子邮件未被他人篡改，并且他们可以信任这些电子邮件由您发送。

您只需获取一个 S/MIME 证书，完成以下步骤，所有外发电子邮件将自动签名。

## 先决条件
- 本插件适用于单用户 WordPress 网站，例如个人博客。多用户网站上，可能正常运行。
- 建议使用 PHP 8.3 及以上版本。请确保您的 PHP 环境已安装 `openssl` 扩展（通常默认安装）。
- 您需要使用其他插件（如 FluentSMTP）通过外部 SMTP 服务器发送邮件。

## 使用方法
### 获取 S/MIME 证书
您可以访问 Actalis 申请为期一年的免费 S/MIME 证书。

申请后，您可以下载包含公钥、私钥和证书链的 `p12` 证书文件。

`p12` 文件的密码将发送至您的邮箱。

### 拆分 p12 证书
假设 p12 文件名为 “smime.p12”，请执行以下命令将其分割为独立的密钥。
- 导出私钥：`openssl pkcs12 -in smime.p12 -nocerts -out smime.key`
  - 系统会提示输入导入密码，即 p12 文件的密码。
  - 随后，您需要输入一个密码来保护导出的私钥。此密码即为插件设置中需要填写的密码。
  - 输入两次以确认。
- 导出公钥：`openssl pkcs12 -in smime.p12 -clcerts -nokeys -out smime.crt`
  - 系统将提示输入导入密码，即 p12 文件的密码。
- 导出证书链：`openssl pkcs12 -in smime.p12 -cacerts -nokeys -out certchain.pem`
  - 系统将提示输入导入密码，即 p12 文件的密码。
  
### 设置权限
上传密钥文件到服务器后，需要为每个文件设置权限。
- 私钥：`chmod 640 smime.key`
- 公钥：`chmod 644 smime.crt`
- 证书链：`chmod 644 certchain.pem`

### 插件设置
安装并激活插件后，设置页面将出现在左侧，并带有盾牌图标。

您需要填写路径和密码字段，点击保存，即可完成设置。

您可以使用 SMTP 插件中的「发送测试邮件」功能进行测试。

卸载插件后，设置将自动删除。
