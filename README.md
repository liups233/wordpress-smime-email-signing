# WordPress S/MIME Email Signing Plugin

English | [简体中文](https://github.com/liups233/wordpress-smime-email-signing/blob/main/README.zh-CN.md) | [繁體中文](https://github.com/liups233/wordpress-smime-email-signing/blob/main/README.zh-TW.md)

A WordPress plugin for signing all outbound emails with S/MIME certificate

Hello. This is the first WordPress plugin I made. Thank you for using it. 

My blog: [Liups233の小站](https://www.liups.net)

WordPress plugin release page: [S/MIME Email Signing](https://wordpress.org/plugins/smime-email-signing/)

## What, Why and How
S/MIME is a protocol used for encrypting, decrypting, or signing E-mails. And This plugin focuses on the signing feature. 

Signing emails can tell the recipients that your emails are not edited by other people, and they can trust the emails are sent by you. 

You just need to get a S/MIME certificate, finish the following parts, and all the oudbound emails will be signed automaticaclly. 

## Prerequisite
- This plugin is for single-user WordPress site, such as a personal blog. I don't know what will happen if you put it in a multi-user site.
- PHP 8.3 and above are recommended. Make sure your PHP has `openssl` extension, which is usually installed by default.
- You should use another plugin, such as FluentSMTP, to send emails through an external SMTP server. 

## Usage
### Get a S/MIME certificate
You can go to Actalis to apply for a free S/MIME certificate for one year. 

After applying, you can download a `p12` certificate file, which contains public key, private key, and certificate chain. 

The password for the `p12` file will be sent to your email. 

### Split the p12 file
In case the p12 file is named "smime.p12", do the following commmands to split it into seperated keys. 
- Export the private key: `openssl pkcs12 -in smime.p12 -nocerts -out smime.key`
  - You will be asked for the Import password, which is the password to the p12 file.
  - Then, you need to enter a password to protect the exported private key. This password is what we will fill in the plugin setting.
  - Enter twice to confirm it.
- Export the public key: `openssl pkcs12 -in smime.p12 -clcerts -nokeys -out smime.crt`
  - You will be asked for the Import password, which is the password to the p12 file.
- Export the certificate chain: `openssl pkcs12 -in smime.p12 -cacerts -nokeys -out certchain.pem`
  - You will be asked for the Import password, which is the password to the p12 file.

### Set correct permission
After uploading the seperated key files to your server, you have to set correct permission to each file. 
- Private key: `chmod 640 smime.key`
- Public key: `chmod 644 smime.crt`
- Certificate chain: `chmod 644 certchain.pem`

### Plugin settings
Install and activate the plugin, the setting page will appear on the left, with a sheild icon.

What you need to do is filling the path and password fields, click Save, and everything is OK. 

You can use the Email Send Test function in your SMTP plugin to test it. 

The options will be deleted automatically after uninstalling the plugin. 

## To-Do List
- [ ] Use WordPress salt function to make the password of the secret key safer. 
