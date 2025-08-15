# 使用官方 PHP 镜像
FROM php:7.4-fpm

# 安装必需的 PHP 扩展
RUN docker-php-ext-install mysqli

# 设置工作目录
WORKDIR /var/www/html

# 将所有靶场文件复制到容器
COPY ./web /var/www/html

# 设置容器启动时执行的命令
CMD ["php-fpm"]
