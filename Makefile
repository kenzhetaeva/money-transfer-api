.PHONY: up down restart build logs bash composer install migrate fresh test

up:
	docker compose up -d

down:
	docker compose down

restart:
	docker compose down
	docker compose up -d

build:
	docker compose build

logs:
	docker compose logs -f

bash:
	docker compose exec php bash

composer:
	docker compose exec php composer install

install:
	docker compose exec php composer install
	docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction

migrate:
	docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction

fresh:
	docker compose exec php php bin/console doctrine:database:drop --force --if-exists
	docker compose exec php php bin/console doctrine:database:create
	docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction

test:
	docker compose exec php php bin/phpunit