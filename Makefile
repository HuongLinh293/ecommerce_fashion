SHELL := /bin/bash

.PHONY: help setup deps assets key migrate serve

help:
	@echo "Available targets: setup deps assets key migrate serve"

setup: deps assets key migrate

deps:
	@if [ ! -f composer.json ]; then echo "No composer.json found"; exit 1; fi
	composer install
	if [ -f package.json ]; then npm ci; fi

assets:
	@if [ -f package.json ]; then npm run dev; else echo "No assets to build"; fi

key:
	php artisan key:generate

migrate:
	php artisan migrate --seed

serve:
	php artisan serve
