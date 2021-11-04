# Пример вложенных команд
# В init происходит полная пересборка проекта
init: docker-down-clear docker-down docker-pull docker-build docker-up api-init
up: docker-up
down: docker-down
restart: down up
lint: api-lint
psalm: api-psalm
test: api-test
test-unit: api-test-unit
test-unit-coverage: api-test-unit-coverage
test-functional-coverage: api-test-functional-coverage
test-functional: api-test-functional

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

# Очистка оставшегося мусора от сервисов
docker-down-clear:
	docker-compose down -v --remove-orphans

# Скачивает обновления всех контейнеров из рееста docker hub
docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

api-test:
	docker-compose run --rm api-php-cli composer test

api-test-unit:
	docker-compose run --rm api-php-cli composer test -- --testsuite=unit

api-test-unit-coverage:
	docker-compose run --rm api-php-cli composer test-coverage -- --testsuite=unit

api-test-functional:
	docker-compose run --rm api-php-cli composer test -- --testsuite=functional

api-test-functional-coverage:
	docker-compose run --rm api-php-cli composer test-coverage -- --testsuite=functional

api-lint:
	docker-compose run --rm api-php-cli composer lint
	docker-compose run --rm api-php-cli composer cs-check

api-psalm:
	docker-compose run --rm api-php-cli composer psalm

api-init: api-composer-install

api-composer-install:
	docker-compose run --rm api-php-cli composer install