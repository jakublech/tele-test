DOCKER_COMPOSE = docker compose
EXEC_PHP = $(DOCKER_COMPOSE) exec app
EXEC_NODE = $(DOCKER_COMPOSE) exec node
SYMFONY = $(EXEC_PHP) php bin/console
COMPOSER = $(EXEC_PHP) composer

build:
	$(DOCKER_COMPOSE) build

up:
	$(DOCKER_COMPOSE) up -d

start: build up

down:
	$(DOCKER_COMPOSE) down

destroy:
	$(DOCKER_COMPOSE) down -v --remove-orphans

ps:
	$(DOCKER_COMPOSE) ps

logs:
	$(DOCKER_COMPOSE) logs -f

logs-app:
	$(DOCKER_COMPOSE) logs -f app

logs-db:
	$(DOCKER_COMPOSE) logs -f db

logs-nginx:
	$(DOCKER_COMPOSE) logs -f nginx

sh:
	$(EXEC_PHP) bash

npm-build:
	$(EXEC_NODE) npm run build

composer:
	$(COMPOSER) $(filter-out $@,$(MAKECMDGOALS))

req:
	$(COMPOSER) require $(filter-out $@,$(MAKECMDGOALS))

vendor:
	$(COMPOSER) install

cc:
	$(SYMFONY) cache:clear

init: vendor

# Catch-all rule to handle arguments for commands
%:
	@:
