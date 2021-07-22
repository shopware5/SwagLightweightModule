.DEFAULT_GOAL := help

filter := "default"
dirname := $(notdir $(CURDIR))
envprefix := $(shell echo "$(dirname)" | tr A-Z a-z)
envname := $(envprefix)test

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
.PHONY: help

install-plugin: .refresh-plugin-list ## Install and activate the plugin
	@echo "Install the plugin"
	./../../../bin/console sw:plugin:install $(dirname) --activate -c

fix-cs: ## Run the code style fixer
	./../../../vendor/bin/php-cs-fixer fix

fix-cs-dry: ## Run the code style fixer in dry mode
	./../../../vendor/bin/php-cs-fixer fix --dry-run -v

.refresh-plugin-list:
	@echo "Refresh the plugin list"
	./../../../bin/console sw:plugin:refresh
