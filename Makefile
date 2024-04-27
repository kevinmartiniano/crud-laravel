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

test-filter:
	vendor/bin/sail test --filter=$(filter)

bash:
	docker exec -it crud-laravel-laravel.test-1 sh

run:
	docker exec -it crud-laravel-laravel.test-1 $(exec)
