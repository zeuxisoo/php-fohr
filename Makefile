all:
	@echo "make install"

install:
	curl -s https://getcomposer.org/installer | php
	php composer.phar install

	cp config/default.php.sample config/default.php
	cp tools/migrate/migrate/schema_version.php.sample tools/migrate/migrate/schema_version.php
