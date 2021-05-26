#!make
include .env
export $(shell sed 's/=.*//' .env)

test:
	- ./vendor/bin/phpunit

stan:
	./vendor/bin/phpstan analyse --memory-limit=2G