# Messaging API homework

## To get my homework up and running

- git clone https://github.com/edmundss/message-api-hw.git
- cd message-api-hw
- cp .env.example .env
- composer install (you may use --ignore-platform-req=ext-sockets)
- bash ./vendor/laravel/sail/bin/sail up -d
- bash ./vendor/laravel/sail/bin/sail artisan migrate

## To run automated tests

- mkdir tests/Unit
- bash ./vendor/laravel/sail/bin/sail artisan test

## To seed DB with test data for pagination and filter tests in swagger

bash ./vendor/laravel/sail/bin/sail artisan db:seed
