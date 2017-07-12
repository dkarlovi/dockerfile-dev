.PHONY: ci composer cs validate-cs phpunit phpunit-coverage

dist: composer cs phpunit
ci: composer validate-cs phpunit-coverage

composer:
	composer validate

cs:
	vendor/bin/php-cs-fixer fix -vvv --diff

validate-cs:
	vendor/bin/php-cs-fixer fix -vvv --diff --dry-run

phpunit:
	vendor/bin/phpunit

phpunit-coverage:
	vendor/bin/phpunit --coverage-clover build/logs/clover.xml
