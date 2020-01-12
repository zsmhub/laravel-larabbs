<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

# 项目运行注意:
1. pull本项目后，需将 .env.example 文件名更改为 .env
2. 配置 .env 文件中的数据库配置参数、邮件配置参数等
3. 执行数据库迁移和数据填充
```
php artisan migrate:refresh --seed
```
4. 在终端执行以下命令
```
# cd 到你的项目下，记得将下面的路径更换为你自己的项目路径
cd ~/Code/your_project

# 启动常驻进程：任务队列监控[创建/编辑话题时会用到]
# 查看任务队列运行状态网址 (http://your_host/horizon)
php artisan horizon

```
5. 在浏览器运行你配置好的域名即可看到效果图
> 使用虚拟域名进行邮件发送，很大概率会被当做垃圾邮件哦。

# LaraBBS 网站效果图:
1. 未登录首页
![未登录首页](https://github.com/zsmhub/markdown-images/blob/master/QQ20200112-110122@2x.png?raw=true)
2. 已登录首页
![已登录首页](https://github.com/zsmhub/markdown-images/blob/master/QQ20200112-110204@2x.png?raw=true)
3. 个人中心
![个人中心](https://github.com/zsmhub/markdown-images/blob/master/QQ20200112-110354@2x.png?raw=true)
4. 话题详情页
![话题详情页](https://github.com/zsmhub/markdown-images/blob/master/QQ20200112-110442@2x.png?raw=true)
5. 管理后台
![管理后台](https://github.com/zsmhub/markdown-images/blob/master/QQ20200112-110543@2x.png?raw=true)
