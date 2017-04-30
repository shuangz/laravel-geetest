# laravel-geetest
geetest验证功能的封装

# 安装方法
使用 composer 安装

# 使用方法
注册服务提供者：在 config/app.php 的 provider 字段中添加 Shuangz\Geetest\GeetestServiceProvider::class

复制配置文件：使用`php artisan vendor:publish` 命令经配置文件复制到config/geetest.php

配置AppID和AppKey：打开 config/geetest.php 安装说明填写相关配置