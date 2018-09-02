##
## Variables
## -----
##

SYMFONY=bin/console
RELOAD_DATABASE_TEST?=true
CI?=false

##
## Project
## -----
##

.PHONY: install
install: vendor assets ## Install the project

.PHONY: assets
assets: node_modules ## Build the assets
	@yarn run encore dev

.PHONY: watch
watch: node_modules ## Watch assets
	@yarn run encore dev --watch

.PHONY: db
db: vendor ## Init the database
	@$(SYMFONY) doctrine:database:drop --if-exists --force
	@$(SYMFONY) doctrine:database:create --if-not-exists
	@$(SYMFONY) doctrine:migrations:migrate --no-interaction --allow-no-migration
	@$(SYMFONY) doctrine:fixtures:load --no-interaction

.PHONY: clean ## Remove generated files
clean:
	@rm -rf .env vendor node_modules

##
## rules based on files
## -----
##

composer.lock: composer.json
	@composer update --lock --no-scripts --no-interaction
	@touch -c composer.lock

vendor: composer.lock
	@composer install

node_modules: yarn.lock
	@yarn install
	@touch -c node_modules

##
## Makefile
## -----
##


.DEFAULT_GOAL := help
default: help

.PHONY: help
help:
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
