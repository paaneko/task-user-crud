PHP_RUN = docker compose run --rm php

init: build composer-install up migrate load-fixtures

build:
	docker compose build

composer-install:
	${PHP_RUN} composer install

composer-update:
	${PHP_RUN} composer update

require:
	${PHP_RUN} composer require

require-dev:
	${PHP_RUN} composer require --dev

up:
	docker compose up -d

down:
	docker compose down --remove-orphans

migrate:
	${PHP_RUN} php bin/console doctrine:migrations:migrate

psalm-check:
	${PHP_RUN} php vendor/bin/psalm --show-info=true

psalm-fix:
	${PHP_RUN} php vendor/bin/psalm --alter --issues=all --dry-run

cs-check:
	${PHP_RUN} php vendor/bin/php-cs-fixer fix --dry-run --diff

cs-fix:
	${PHP_RUN} php vendor/bin/php-cs-fixer fix

test-all:
	${PHP_RUN} composer test

test-unit:
	${PHP_RUN} composer test --testsuite=Unit

test-unit-coverage:
	${PHP_RUN} composer test-coverage --testsuite=unit

infection:
	${PHP_RUN} ./vendor/bin/infection --test-framework-options="--testsuite=Unit" --show-mutations -j8

swagger-generate:
	${PHP_RUN} ./vendor/bin/openapi -o docs/swagger.json src

load-fixtures:
	${PHP_RUN} php bin/console doctrine:fixtures:load