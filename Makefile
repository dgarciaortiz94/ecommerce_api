OS = $(shell uname)
UID = $(shell id -u)
DOCKER_CONTAINER = ecommerce
DOCKER_ROOT_PATH = /var/www/html/ecommerce
NAMESERVER_IP = $(shell ip address \ grep docker0)

help:
	@echo 'usage: make [target]'
	@echo
	@echo 'targets:'
	@egrep '^(.+)\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#'

build:
	docker-compose up -d --remove-orphans --build
	@echo "Waiting for containers to start..."
	@while [ $$(docker ps --filter "status=running" --format "{{.Names}}" | grep -c "${DOCKER_CONTAINER}") -eq 0 ]; do \
		sleep 3; \
	done
	docker exec ${DOCKER_CONTAINER} sh -c "cd ${DOCKER_ROOT_PATH} && chmod 777 -R ."
	cp ./.env.example ./.env.local
	cp ./.env.example ./.env
	docker exec ${DOCKER_CONTAINER} sh -c "cd ${DOCKER_ROOT_PATH} && composer install"

rebuild:
	docker-compose up -d --remove-orphans --build
	@echo "Waiting for containers to start..."
	@while [ $$(docker ps --filter "status=running" --format "{{.Names}}" | grep -c "${DOCKER_CONTAINER}") -eq 0 ]; do \
		sleep 3; \
	done

start:
	docker-compose up -d --remove-orphans

stop:
	docker-compose down --remove-orphans

delete:
	docker rm -f ${DOCKER_CONTAINER}

exec:
	docker exec -it ${DOCKER_CONTAINER} bash

test:
	docker exec ${DOCKER_CONTAINER} sh -c "cd ${DOCKER_ROOT_PATH} && php bin/phpunit"

cs-fix:
	docker exec ${DOCKER_CONTAINER} sh -c "cd ${DOCKER_ROOT_PATH} && vendor/bin/php-cs-fixer fix src"
	
