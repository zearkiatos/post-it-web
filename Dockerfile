FROM composer
WORKDIR /app
COPY . .
RUN rm -rf vendor
RUN composer install
RUN wget https://get.symfony.com/cli/installer -O - | bash && \
    mv /root/.symfony/bin/symfony /usr/local/bin/symfony

RUN apk update && apk upgrade
RUN apk --no-cache add postgresql-dev
RUN apk add --update nodejs npm
RUN docker-php-ext-install pdo pdo_pgsql
RUN curl https://cli-assets.heroku.com/install.sh | sh
    
RUN symfony server:ca:install

RUN composer dump

EXPOSE 8000

ENTRYPOINT ["sh", "./docker/entrypoint-prod.sh"]