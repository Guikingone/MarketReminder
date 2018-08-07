# Constants
DOCKER_COMPOSE = docker-compose
DOCKER = docker
COMPOSER = $(ENV_PHP) composer
GCLOUD = gcloud

## Environments
ENV_PHP = $(DOCKER) exec marketReminder_php-fpm
ENV_NODE = $(DOCKER) exec marketReminder_nodejs
ENV_VARNISH = $(DOCKER) exec marketReminder_varnish
ENV_BLACKFIRE = $(DOCKER) exec marketReminder_blackfire

## Globals commands
start: docker-compose.yml
	$(DOCKER_COMPOSE) build --no-cache
	$(DOCKER_COMPOSE) up -d --build --remove-orphans --force-recreate
	make install
	make cache-clear
	make yarn_install

restart: docker-compose.yml
	$(DOCKER_COMPOSE) up -d --build --remove-orphans --no-recreate
	make install
	make cache-clear
	make yarn_install

stop: docker-compose.yml
	$(DOCKER) stop $(docker ps -a -q)

clean: ## Allow to delete the generated files and clean the project folder
	$(ENV_PHP) rm -rf .env ./node_modules ./vendor

## Tools
php-cs: .php_cs.cache
	$(ENV_PHP) php-cs-fixer fix $(FOLDER) --rules=@$(RULES)

deptrac: depfile.yml
	$(ENV_PHP) deptrac

phpmetrics: ## Allow to launch a phpmetrics analyze
	$(ENV_PHP) vendor/bin/phpmetrics src

phpstan: vendor/bin/phpstan
	$(ENV_PHP) vendor/bin/phpstan analyse $(FOLDER)

gcp-build: cloudbuild.json
	$(GCLOUD) builds submit --config cloudbuild.json

## PHP commands
install: composer.json
	$(COMPOSER) install -a -o
	$(COMPOSER) clear-cache
	$(COMPOSER) dump-autoload --optimize --classmap-authoritative

update: composer.lock
	$(COMPOSER) update -a -o

require: composer.json
	$(COMPOSER) req $(PACKAGE) -a -o

require-dev: composer.json
	$(COMPOSER) req --dev $(PACKAGE) -a -o

remove: composer.lock
	$(COMPOSER) remove $(PACKAGE) -a -o

autoload: composer.json
	$(COMPOSER) dump-autoload -a -o

## Symfony commands
cache-clear: var/cache
	$(ENV_PHP) rm -rf ./var/cache/*

cache-warm: var/cache
	$(ENV_PHP) ./bin/console cache:warmup

translation: translations
	    $(ENV_PHP) ./bin/console app:translation:dump button fr
	    $(ENV_PHP) ./bin/console app:translation:dump button en
	    $(ENV_PHP) ./bin/console app:translation:dump form fr
	    $(ENV_PHP) ./bin/console app:translation:dump form en
	    $(ENV_PHP) ./bin/console app:translation:dump mail fr
	    $(ENV_PHP) ./bin/console app:translation:dump mail en
	    $(ENV_PHP) ./bin/console app:translation:dump messages fr
	    $(ENV_PHP) ./bin/console app:translation:dump messages en
	    $(ENV_PHP) ./bin/console app:translation:dump session fr
	    $(ENV_PHP) ./bin/console app:translation:dump session en
	    $(ENV_PHP) ./bin/console app:translation:dump validators fr
	    $(ENV_PHP) ./bin/console app:translation:dump validators en
	    $(ENV_PHP) ./bin/console app:translation:warm button fr
	    $(ENV_PHP) ./bin/console app:translation:warm button en
	    $(ENV_PHP) ./bin/console app:translation:warm form fr
	    $(ENV_PHP) ./bin/console app:translation:warm form en
	    $(ENV_PHP) ./bin/console app:translation:warm mail fr
	    $(ENV_PHP) ./bin/console app:translation:warm mail en
	    $(ENV_PHP) ./bin/console app:translation:warm messages fr
	    $(ENV_PHP) ./bin/console app:translation:warm messages en
	    $(ENV_PHP) ./bin/console app:translation:warm validators fr
	    $(ENV_PHP) ./bin/console app:translation:warm validators en
	    $(ENV_PHP) ./bin/console app:translation:warm session fr
	    $(ENV_PHP) ./bin/console app:translation:warm session en

translation-warm: translations
	    $(ENV_PHP) ./bin/console app:translation:warm $(CHANNEL) $(LOCALE) --env=$(ENV)

translation-dump: translations
	    $(ENV_PHP) ./bin/console app:translation:dump $(CHANNEL) $(LOCALE) --env=$(ENV)

container: bin/console
	    $(ENV_PHP) ./bin/console debug:container --show-private $(SERVICE)

event: bin/console
	    $(ENV_PHP) ./bin/console debug:event-dispatcher

router: bin/console
	    $(ENV_PHP) ./bin/console d:r

## Doctrine
create-schema: config/doctrine
	    $(ENV_PHP) ./bin/console d:d:d --force --env=$(ENV)
	    $(ENV_PHP) ./bin/console d:d:c --env=$(ENV)
	    $(ENV_PHP) ./bin/console d:s:c --env=$(ENV)

check-schema: config/doctrine
	    $(ENV_PHP) ./bin/console doctrine:schema:validate --env=$(ENV)

update-schema: ## Allow to update the schema
		$(ENV_PHP) ./bin/console d:d:d --force --env=$(ENV)
		$(ENV_PHP) ./bin/console d:d:c --env=$(ENV)
	    $(ENV_PHP) ./bin/console d:s:u --dump-sql --env=$(ENV)
	    $(ENV_PHP) ./bin/console d:s:u --force --env=$(ENV)

fixtures: src/DataFixtures
	    $(ENV_PHP) ./bin/console doctrine:fixtures:load -n --env=${ENV}

doctrine-cache: ## Allow to clean the Doctrine cache
	    $(ENV_PHP) ./bin/console doctrine:cache:clear-query
	    $(ENV_PHP) ./bin/console doctrine:cache:clear-metadata

migrations: config/doctrine
	    $(ENV_PHP) ./bin/console doctrine:migrations:diff
	    $(ENV_PHP) ./bin/console doctrine:migrations:migrate

## Tests
phpunit: tests
	    make update-schema ENV=test
	    make fixtures ENV=test
	    make doctrine-cache
	    $(ENV_PHP) ./bin/console cache:pool:prune
	    $(ENV_PHP) ./bin/phpunit --exclude-group Blackfire,e2e tests/$(FOLDER)

phpunit-e2e: tests
	    make cache-clear
	    make update-schema ENV=test
	    make fixtures ENV=test
	    make doctrine-cache
	    $(ENV_PHP) ./bin/console cache:pool:prune
	    $(ENV_PHP) ./bin/console app:translation:dump messages fr --env=test
	    $(ENV_PHP) ./bin/console app:translation:dump messages en --env=test
	    $(ENV_PHP) ./bin/console app:translation:dump validators fr --env=test
	    $(ENV_PHP) ./bin/console app:translation:dump validators en --env=test
	    $(ENV_PHP) ./bin/console app:translation:dump session fr --env=test
	    $(ENV_PHP) ./bin/console app:translation:dump session en --env=test
	    $(ENV_PHP) ./bin/console app:translation:warm form fr --env=test
	    $(ENV_PHP) ./bin/console app:translation:warm form en --env=test
	    $(ENV_PHP) ./bin/console app:translation:warm mail fr --env=test
	    $(ENV_PHP) ./bin/console app:translation:warm mail en --env=test
	    $(ENV_PHP) ./bin/console app:translation:warm messages fr --env=test
	    $(ENV_PHP) ./bin/console app:translation:warm messages en --env=test
	    $(ENV_PHP) ./bin/console app:translation:warm validators fr --env=test
	    $(ENV_PHP) ./bin/console app:translation:warm validators en --env=test
	    $(ENV_PHP) ./bin/console app:translation:warm session fr --env=test
	    $(ENV_PHP) ./bin/console app:translation:warm session en --env=test
	    $(ENV_PHP) ./bin/console app:translation:warm form fr --env=test
	    $(ENV_PHP) ./bin/console app:translation:warm form en --env=test
	    $(ENV_PHP) ./bin/console app:translation:warm mail fr --env=test
	    $(ENV_PHP) ./bin/console app:translation:warm mail en --env=test
	    $(ENV_PHP) ./bin/phpunit --group e2e

phpunit-blackfire: tests
	    $(ENV_PHP) ./bin/console cache:pool:prune
	    $(ENV_PHP) ./bin/phpunit --group Blackfire tests/$(FOLDER)

behat: features
	    make cache-clear
	    make update-schema ENV=test
	    make fixtures ENV=test
	    make doctrine-cache
	    $(ENV_PHP) ./bin/console cache:pool:prune
	    make translation
	    $(ENV_PHP) vendor/bin/behat --profile $(PROFILE)

## NodeJS commands
yarn_install: node_modules
	    $(ENV_NODE) yarn install

encore_watch: webpack.config.js
	    $(ENV_NODE) yarn watch

encore_production: webpack.config.js
	    $(ENV_NODE) yarn build

yarn_add_global: package.json
	    $(ENV_NODE) yarn global add $(PACKAGE)

yarn_add_prod: package.json
	    $(ENV_NODE) yarn add $(PACKAGE)

yarn_add_dev: package.json
	    $(ENV_NODE) yarn add --dev $(PACKAGE)

## Varnish commands
varnish_logs: ## Allow to see the varnish logs
	$(ENV_VARNISH) varnishlog -b

## Blackfire commands
profile_php: public/index.php
	make cache-clear
	make doctrine-cache ENV=prod
	$(ENV_PHP) ./bin/console cache:pool:prune
	make translation
	$(ENV_BLACKFIRE) blackfire curl http://172.18.0.1:8080$(URL) --samples $(SAMPLES)

profile_varnish: public/index.php
	make cache-clear
	make doctrine-cache ENV=prod
	$(ENV_PHP) ./bin/console cache:pool:prune
	make translation
	$(ENV_BLACKFIRE) blackfire curl http://172.18.0.1$(URL) --samples $(SAMPLES)
