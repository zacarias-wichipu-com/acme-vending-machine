CURRENT_DIR:=$(dir $(abspath $(lastword $(MAKEFILE_LIST))))

include etc/make/variables.mk

# 🔝Main
.PHONY: default
default: info

.PHONY: info
info:
ifneq ($(OS),Windows_NT)
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n"} /^[a-zA-Z0-9_-]+:.*?##/ { printf "  \033[36m%-27s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)
endif

.PHONY: build
build: deps start ## 🏗 Build app.

.PHONY: deps
deps: composer-install ## 🧩 Install Composer dependencies.

# 🐘 Composer
composer-env-file:
	@if [ ! -f .env.local ]; then echo '' > .env.local; fi

 .PHONY: composer-require
composer-require: DOCKER_COMMAND=require ## 🧩 Composer require.
composer-require: INTERACTIVE=-ti --interactive
composer-require: DOCKER_COMMAND_OPTIONS=--ignore-platform-reqs

 .PHONY: composer-update
composer-update: DOCKER_COMMAND=update ## 🧩 Composer update.
composer-update: INTERACTIVE=-ti --interactive
composer-update: DOCKER_COMMAND_OPTIONS=--ignore-platform-reqs

.PHONY: composer-require-dev
composer-require-dev: DOCKER_COMMAND=require --dev ## 🧩 Composer require dev.
composer-require-dev: INTERACTIVE=-ti --interactive
composer-require-dev: DOCKER_COMMAND_OPTIONS=--ignore-platform-reqs

.PHONY: composer-install
composer-install: DOCKER_COMMAND=install ## 🧩 Composer install.
composer-install: DOCKER_COMMAND_OPTIONS=--ignore-platform-reqs

.PHONY: composer-cache-clear
composer-cache-clear: DOCKER_COMMAND=run cache-clear ## 🧩 Symfony cache-clear.

.PHONY: composer
composer composer-install composer-require composer-require-dev composer-update: composer-env-file
	@docker run --rm $(INTERACTIVE) --volume $(CURRENT_DIR):/app --user $(id -u):$(id -g) \
		composer:2 $(DOCKER_COMMAND) \
			$(DOCKER_COMMAND_OPTION) \
			--no-ansi

# 🐳 Docker Compose
.PHONY: start
start: DOCKER_COMMAND=up --build -d ## ▶️ Up container.

.PHONY: stop
stop: DOCKER_COMMAND=stop ## ⏹ Stop container.

.PHONY: destroy
destroy: DOCKER_COMMAND=down

.PHONY: status
status:DOCKER_COMMAND=ps ## 📈 Containers status

# Usage: `make doco DOCKER_COMMAND="ps --services"`
# Usage: `make doco DOCKER_COMMAND="build --parallel --pull --force-rm --no-cache"`
.PHONY: doco
doco start stop destroy status: composer-env-file
	USER_ID=${shell id -u} GROUP_ID=${shell id -g} docker-compose $(DOCKER_COMMAND)

.PHONY: rebuild
rebuild: composer-env-file
	docker-compose build --pull --force-rm --no-cache
	make deps
	make start

# ✅ Tests
.PHONY: u-tests
u-tests: composer-env-file ## ✅  Unit tests
	@echo "${INFO_PROMPT_INIT}Run unit tests...${INFO_PROMPT_END}"
	@docker exec php ./vendor/bin/phpunit --colors=always --group unit

.PHONY: i-tests
i-tests: composer-env-file ## ✅  Integration tests
	@echo "${INFO_PROMPT_INIT}Run integration tests...${INFO_PROMPT_END}"
	@docker exec php ./vendor/bin/phpunit --colors=always --group integration

.PHONY: a-tests
a-tests: composer-env-file ## ✅  Application tests
	@echo "${INFO_PROMPT_INIT}Run integration tests...${INFO_PROMPT_END}"
	@docker exec php ./vendor/bin/phpunit --colors=always --group application

.PHONY: tests
tests: composer-env-file u-tests i-tests a-tests## ✅  All tests
##  init-db-test doctrine-migrate-db-test

# ⚒️ Utils
.PHONY: cache-clear
cache-clear: ##   Clears symfony cache
	@echo "${INFO_PROMPT_INIT}Clearing cache...${INFO_PROMPT_END}"
	@docker run --rm -t --volume $(CURRENT_DIR):/app --user $(id -u):$(id -g) \
		composer:2 run post-install-cmd

.PHONY: xdebug-enable
xdebug-enable: ## 🧰 Enable xDebug
	@echo "${INFO_PROMPT_INIT}Enabling xdebug...${INFO_PROMPT_END}"
	@docker exec -u 0 php sh -c "sed -i 's|xdebug.mode = .*|xdebug.mode = develop,debug|g' /usr/local/etc/php/conf.d/xdebug.ini"
	@docker exec -u 0 php sh -c "sed -i 's|xdebug.start_with_request = .*|xdebug.start_with_request = yes|g' /usr/local/etc/php/conf.d/xdebug.ini"
	@echo "${INFO_PROMPT_INIT}Fixing xdebug...${INFO_PROMPT_END}"
	@docker exec -u 0 php sh -c "sed -i 's|xdebug.client_host = .*|xdebug.client_host = host.docker.internal|g' /usr/local/etc/php/conf.d/xdebug.ini"
	@docker exec -u 0 php sh -c "cat /usr/local/etc/php/conf.d/xdebug.ini"
	@echo "${INFO_PROMPT_INIT}Restarting xdebug on...${INFO_PROMPT_END}"
	@$(MAKE) stop
	@$(MAKE) start

.PHONY: xdebug-disable
xdebug-disable: ## 📴 Disable xDebug
	@echo "${INFO_PROMPT_INIT}Disabling xdebug...${INFO_PROMPT_END}"
	@docker exec -u 0 php sh -c "sed -i 's|xdebug.mode = .*|xdebug.mode = off|g' /usr/local/etc/php/conf.d/xdebug.ini"
	@docker exec -u 0 php sh -c "sed -i 's|xdebug.start_with_request = .*|xdebug.start_with_request = no|g' /usr/local/etc/php/conf.d/xdebug.ini"
	@docker exec -u 0 php sh -c "cat /usr/local/etc/php/conf.d/xdebug.ini"
	@echo "${INFO_PROMPT_INIT}Restarting xdebug off...${INFO_PROMPT_END}"
	@$(MAKE) stop
	@$(MAKE) start

.PHONY: phpstan
phpstan: ## 📊 PHPStan (make psalm PHPSTAN_OPTIONS="--help")
	@echo "${INFO_PROMPT_INIT}Run PHPStan static code analysis...${INFO_PROMPT_END}"
	@docker exec -t php ./vendor/bin/phpstan analyse --no-progress ${PHPSTAN_OPTIONS}

.PHONY: psalm
psalm: ## 📊 Psalm (make psalm PSALM_OPTIONS="--help")
	@echo "${INFO_PROMPT_INIT}Run Psalm static code analysis...${INFO_PROMPT_END}"
	@docker exec -t php ./vendor/bin/psalm --no-progress ${PSALM_OPTIONS}

.PHONY: phpmd
phpmd: ## 📊 Psalm (make psalm PSALM_OPTIONS="--help")
	@echo "${INFO_PROMPT_INIT}Run PHPMD static code analysis...${INFO_PROMPT_END}"
	@docker exec -t php ./vendor/bin/phpmd apps,src,tests ansi phpmd.xml ${PSALM_OPTIONS}

.PHONY: code-static-analyse
code-static-analyse: phpstan psalm phpmd ## 📊 Code static analysis with PHPStan, Psalm and PHPMD

.PHONY: ecs-check
ecs-check: ## 🖋️ Check code standards with ecs (make ecs ECS_OPTIONS="--help")
	@echo "${INFO_PROMPT_INIT}Run ecs code standards check...${INFO_PROMPT_END}"
	@docker exec -t php ./vendor/bin/ecs --no-progress-bar ${ECS_OPTIONS}

.PHONY: ecs-fix
ecs-fix: ## 🖋️ Fix code standards with ecs (make ecs ECS_OPTIONS="--help")
	@echo "${INFO_PROMPT_INIT}Run ecs standards fix...${INFO_PROMPT_END}"
	@docker exec -t php ./vendor/bin/ecs --no-progress-bar --fix ${ECS_OPTIONS}

.PHONY: shell-php
shell-php: ## 💻 php container shell
	@docker exec -it php sh

.PHONY: vm
vm: ## 🎰 Vending machine

.PHONY: vm-init
vm-init: VENDING_MACHINE_COMMAND=machine\:init ## Init vending machine

.PHONY: vm-print
vm-print: VENDING_MACHINE_COMMAND=machine\:print ## Print vending machine

.PHONY: vm-customer-add-coin
vm-customer-add-coin: VENDING_MACHINE_COMMAND=customer\:coin\:add ## Customer add a coin

.PHONY: vm-customer-refund-coins
vm-customer-refund-coins: VENDING_MACHINE_COMMAND=customer\:coins\:refund ## Refund customer coins

.PHONY: vm-service-mode-enable
vm-service-mode-enable: VENDING_MACHINE_COMMAND=service\:enable ## Enable vending machine service mode

.PHONY: vm-service-mode-disable
vm-service-mode-disable: VENDING_MACHINE_COMMAND=service\:disable ## Disable vending machine service mode

vm vm-init vm-print vm-customer-add-coin vm-customer-refund-coins vm-service-mode-enable vm-service-mode-disable:
	@docker exec -it php bin/vending-machine ${VENDING_MACHINE_COMMAND}
