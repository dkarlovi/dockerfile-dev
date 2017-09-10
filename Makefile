.PHONY: ci composer cs validate-cs phpunit phpunit-coverage phpstan split

dist: composer cs phpstan phpunit
ci: composer validate-cs phpstan phpunit-coverage

composer:
	composer validate

cs:
	vendor/bin/php-cs-fixer fix -vvv --diff

validate-cs:
	vendor/bin/php-cs-fixer fix -vvv --diff --dry-run

phpunit:
	vendor/bin/phpunit

phpunit-coverage:
	vendor/bin/phpunit --coverage-text --coverage-clover build/logs/clover.xml

phpstan:
	vendor/bin/phpstan analyse . --level 7 --configuration phpstan.neon

split:
	bin/subtree-split
