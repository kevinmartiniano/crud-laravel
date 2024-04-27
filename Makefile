# Application Makefile

up:
	vendor/bin/sail up -d

down:
	vendor/bin/sail stop

restart:
	vendor/bin/sail restart

ps:
	vendor/bin/sail ps

composer-install:
	vendor/bin/sail composer install

test:
	vendor/bin/sail test