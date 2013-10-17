all:
	@echo "make install"

install:
	curl -s https://getcomposer.org/installer | php
	php composer.phar install

	mv config/default.php.sample config/default.php
