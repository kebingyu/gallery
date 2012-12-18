gallery
=======

This is a gallery application built on top of Yii framework (version 1.1.12). I am working slowly towards full blown.

apache conf

<VirtualHost *:80>
    ServerName yii.kyutest.com
    ServerAlias yii.kyutest.com
    ServerAdmin kyu@fg.com
    DocumentRoot "/var/www/sites/yii/gallery"
    <Directory "/var/www/sites/yii/gallery">
        Options ExecCGI FollowSymLinks
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
    SetEnv YII_CONFIG "/var/www/sites/yii/gallery/protected/config/main.php"
</VirtualHost>

