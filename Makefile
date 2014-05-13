all:
	@echo "make install"
	@echo "make update"
	@echo "make server"
	@echo "make clean"

install:
	curl -s https://getcomposer.org/installer | php
	php composer.phar install

	cp config/default.php.sample config/default.php

update:
	php composer.phar update

server:
	php -S localhost:8080

clean:
	find ./cache/view/* -type d -maxdepth 0 -exec rm -rf {} \;

database:
	./vendor/bin/phpmig migrate
