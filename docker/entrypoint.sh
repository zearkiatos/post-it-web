php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
symfony server:start --allow-http --no-tls --port=8000