all:
	@echo "make install"

install:
	curl -s https://getcomposer.org/installer | php
	php composer.phar install
