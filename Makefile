all:
	@echo "make composer"
	@echo "make deps"

composer:
	curl -sS https://getcomposer.org/installer | php

deps:
	php composer.phar install

server:
	php -S localhost:8080 -t public
