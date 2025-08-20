=== S/MIME Email Signing ===
Contributors: liups12138
Donate link: https://paypal.me/liups1213?country.x=C2&amp;locale.x=zh_XC
Tags: S/MIME, E-Mail, Signing
Requires at least: 6.7
Tested up to: 6.8
Stable tag: 1.0
Requires PHP: 7.2
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
A plugin for signing all outbound emails with S/MIME certificate. 

== Installation ==
= Prerequisite =
1. This plugin is for single-user WordPress site, such as a personal blog. I don't know what will happen if you put it in a multi-user site.

2. PHP 8.3 and above are recommended. Make sure your PHP has openssl extension, which is usually installed by default. 

3. You should use another plugin, such as FluentSMTP, to send emails through an external SMTP server. 

= Get a S/MIME certificate =
You can go to Actalis to apply for a free S/MIME certificate for one year. 

After applying, you can download a p12 certificate file, which contains public key, private key, and certificate chain. 

The password to the p12 file will be sent to your email. 

= Split the p12 file =
In case the p12 file is named "smime.p12", do the following commmands to split it into seperated keys.

1. Export the private key: `openssl pkcs12 -in smime.p12 -nocerts -out smime.key`
- You will be asked for the Import password, which is the password to the p12 file.
- Then, you need to enter a password to protect the exported private key. This password is what we will fill in the plugin setting.
- Enter twice to confirm it.

2. Export the public key: `openssl pkcs12 -in smime.p12 -clcerts -nokeys -out smime.crt`
- You will be asked for the Import password, which is the password to the p12 file.

3. Export the certificate chain: `openssl pkcs12 -in smime.p12 -cacerts -nokeys -out certchain.pem`
You will be asked for the Import password, which is the password to the p12 file.

= Set correct permission =
After uploading the seperated key files to your server, you have to set correct permission to each file.

1. Private key: `chmod 640 smime.key`

2. Public key: `chmod 644 smime.crt`

3. Certificate chain: `chmod 644 certchain.pem`

= Plugin settings =
Install and activate the plugin, the setting page will appear on the left, with a sheild icon.

What you need to do is filling the path and password fields, click Save, and everything is OK.

You can use the Email Send Test function in your SMTP plugin to test it.

The options will be deleted automatically after uninstalling the plugin.
