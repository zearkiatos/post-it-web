FROM composer
WORKDIR /app
COPY . .
RUN rm -rf vendor
RUN composer install
RUN wget https://get.symfony.com/cli/installer -O - | bash && \
    mv /root/.symfony/bin/symfony /usr/local/bin/symfony

RUN apk update && apk upgrade
RUN docker-php-ext-install pdo_mysql
    
RUN symfony server:ca:install

RUN composer dump

EXPOSE 8000

ENTRYPOINT ["sh", "./docker/entrypoint.sh"]