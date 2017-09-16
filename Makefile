.PHONY: ci composer cs validate-cs phpunit phpunit-coverage phpstan split

dist: composer cs phpstan phpunit phpcpd
ci: composer validate-cs phpstan phpunit-coverage

composer:
	composer validate

cs:
	vendor/bin/php-cs-fixer fix -vvv --diff

validate-cs:
	vendor/bin/php-cs-fixer fix -vvv --diff --dry-run

phpcpd:
	vendor/bin/phpcpd . --fuzzy --min-lines=3 --min-tokens=50 --exclude=vendor --exclude=src/dockerfile/vendor --exclude=src/dockerfile-builder/vendor

phpunit:
	vendor/bin/phpunit --testdox

phpunit-coverage:
	vendor/bin/phpunit --testdox --coverage-text --coverage-clover build/logs/clover.xml

phpstan:
	vendor/bin/phpstan analyse . --level 7 --configuration phpstan.neon

split:
	bin/subtree-split
