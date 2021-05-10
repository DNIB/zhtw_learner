#!/usr/bin/make -f

.env: cp .env.example .env

vendor:
	composer.lock
	composer install

# Initial Project Setting
.PHONY: init.project
init.project:
	make vendor
	make .env
	php artisan key:generate
	make service.up

# Create and start all containers.
.PHONY: service.up
service.up:
	cd laradock/ && cp .env.example .env
	cd laradock/ && docker-compose up -d nginx mysql redis workspace

# Build the project and image.
.PHONY: service.build
service.build:
	php artisan serve --host 127.0.0.1 --port 8060

# Run test.
.PHONY: test
test:
	vendor/bin/phpunit

# Refresh database and making seeder
.PHONY: database.fresh
database.fresh:
	php artisan migrate:fresh
	php artisan db:seed
