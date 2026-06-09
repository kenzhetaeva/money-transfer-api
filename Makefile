.PHONY: up down restart composer install migrate test

up:
	docker compose up -d

down:
	docker compose down

restart:
	docker compose down
	docker compose up -d

composer:
	docker compose exec php composer install

install:
	docker compose exec php composer install
	docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction

migrate:
	docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction

test:
	docker compose exec php php bin/phpunit
