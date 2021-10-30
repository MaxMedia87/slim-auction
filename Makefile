# Пример вложенных команд
# В init происходит полная пересборка проекта
init: docker-down-clear docker-down docker-pull docker-build docker-up api-init
up: docker-up
down: docker-down
restart: down up
lint: api-lint
psalm: api-psalm
test: api-test

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

api-lint:
	docker-compose run --rm api-php-cli composer lint
	docker-compose run --rm api-php-cli composer cs-check

api-psalm:
	docker-compose run --rm api-php-cli composer psalm

api-init: api-composer-install

api-composer-install:
	docker-compose run --rm api-php-cli composer install