

﻿# 应用案例

案例网址：
[通用查询的案例](http://hk.r34.cc/index.php/Qwadmin/Vi/uniquerydata/rpw/excel/sheetname/%E9%80%9A%E7%94%A8%E7%B3%BB%E7%BB%9F%E5%BA%94%E7%94%A8%E6%A1%88%E4%BE%8B)下表大多有教程，欢迎加入QQ群：539844557。

现在可以与gitee自动同步

| 应用类型       | 重要性   | 应用名                                                       |
| -------------- | -------- | ------------------------------------------------------------ |
| 安装及安全优化 | 必学     | [前期安装](http://hk.r34.cc/index.php/Qwadmin/Vi/uniquerydata/rpw/excel?d3=前期安装) |
| 表格共享       | 必学     | [Excel网络共享](http://hk.r34.cc/index.php/Qwadmin/Vi/uniquerydata/rpw/excel?d3=Excel网络共享) |
| 表格共享       | 必学     | [多条件查询](http://hk.r34.cc/index.php/Qwadmin/Vi/uniquerydata/rpw/excel?d3=多条件查询) |
| 通用查询       | 建议学会 | [教师通讯录、学生信息查询](http://hk.r34.cc/index.php/Qwadmin/Vi/uniquerydata/rpw/excel?d3=教师通讯录_学生信息查询) |
| 安装及安全优化 | 建议学会 | [自建服务器系统（旧）](http://hk.r34.cc/index.php/Qwadmin/Vi/uniquerydata/rpw/excel?d3=自建服务器系统_旧版) |
| 数据整理       | 建议学会 | [数据、文件台帐化管理](http://hk.r34.cc/index.php/Qwadmin/Vi/uniquerydata/rpw/excel?d3=数据、文件台帐化管理) |
| 通用查询       | 建议学会 | [查询APP制作](http://hk.r34.cc/index.php/Qwadmin/Vi/uniquerydata/rpw/excel?d3=查询APP) |
| 表格共享       |          | [猎头跟进系统](http://hk.r34.cc/index.php/Qwadmin/Vi/uniquerydata/rpw/excel?d3=猎头跟进系统) |
| 万能表单       |          | [万能表单查询](http://hk.r34.cc/index.php/Qwadmin/Vi/uniquerydata/rpw/excel?d3=万能表单查询) |
| 万能表单       |          | [古村落调研实时资料录入](http://hk.r34.cc/index.php/Qwadmin/Vi/uniquerydata/rpw/excel?d3=古村落调研实时资料录入) |
| 通用查询       |          | [千万级数据查询](http://hk.r34.cc/index.php/Qwadmin/Vi/uniquerydata/rpw/excel?d3=千万级数据查询) |
| 表格共享       |          | [多表汇总](http://hk.r34.cc/index.php/Qwadmin/Vi/uniquerydata/rpw/excel?d3=多表汇总) |
| 表格共享       |          | [多条件查询_旧版 ](http://hk.r34.cc/index.php/Qwadmin/Vi/uniquerydata/rpw/excel?d3=多条件查询_旧版) |
| 安装及安全优化 |          | [系统配置的安全优化](http://hk.r34.cc/index.php/Qwadmin/Vi/uniquerydata/rpw/excel?d3=系统配置的安全优化) |
| 安装及安全优化 |          | [虚拟局域网的构建n2n](http://hk.r34.cc/index.php/Qwadmin/Vi/uniquerydata/rpw/excel?d3=虚拟局域网的构建n2n) |
| 安装及安全优化 |          | [路由器异地组网](http://hk.r34.cc/index.php/Qwadmin/Vi/uniquerydata/rpw/excel?d3=路由器异地组网) |
| 通用查询       |          | [权限管理](http://hk.r34.cc/index.php/Qwadmin/Vi/uniquerydata/rpw/excel?d3=权限管理) |



下图是最刚开始做的，也是一直在用的通讯录查询系统。   
![44](http://vps0.upsir.com/img/txl.png)

# 问答
### 默认的账号密码是什么？
php网站的账号密码是自己建的，用户名一般为admin。
mysql网站的账号密码为root, 密码为 root。
kod的文件管理系统的密码为admin, 密码为 admin。

# 问答
### 能否保存敏感数据？
我至少自己就在保存，但我也不敢保证绝对安全。

现在自己组建虚拟局域网来保证数据处于内部，保证数据安全，但几年的外网使用也没出安全问题。

### 外网能否访问？
可以运行frp服务，可以网上找个免费的。加群可以了解这方面的信息。一般不建议发布在外网，安全性较差。

### debian可以一键安装程序
sudo apt update && sudo apt install -y unzip && wget http://pub.r34.cc/toolsoft/excelquery_docker.zip -O excelquery_docker.zip && unzip excelquery_docker.zip && cd excelquery_docker && docker-compose up -d

### docker compose安装
```
version: '3'
services:
  web:
    image: cjie.eu.org/lilyhcn1/php74apache_excelquery:latest
    ports:
      - "8080:80"
    volumes:
      - ./webroot:/var/www/html
    depends_on:
      - db
    networks:
      - app-network

  db:
    image: cjie.eu.org/mysql:5.6
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: excelquery
      MYSQL_USER: user
      MYSQL_PASSWORD: password
    volumes:
      - ./mysqldata:/var/lib/mysql
    command: >
      --character-set-server=utf8
      --collation-server=utf8_general_ci
      --skip-character-set-client-handshake
      --init-connect='SET NAMES utf8'
      --innodb-buffer-pool-size=64M
      --innodb-log-buffer-size=1M
      --key-buffer-size=8M
      --query-cache-size=0
      --query-cache-type=0
      --performance-schema=0
      --max-connections=20
      --thread-stack=128K
      --tmp-table-size=8M
      --max-heap-table-size=8M
      --innodb-doublewrite=0
    networks:
      - app-network

networks:
  app-network:
    driver: bridge
```

