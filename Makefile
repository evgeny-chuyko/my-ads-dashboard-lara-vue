.PHONY: help up down restart build logs shell-php shell-node install migrate seed test test-feature test-unit test-auth test-publisher test-admin test-coverage test-parallel queue-work queue-listen queue-restart queue-failed queue-retry clean

help:
	@echo "MyAds Dashboard - Makefile Commands"
	@echo ""
	@echo "Docker:"
	@echo "  make up          - Start all containers"
	@echo "  make down        - Stop all containers"
	@echo "  make restart     - Restart all containers"
	@echo "  make build       - Rebuild containers"
	@echo "  make logs        - Show logs from all containers"
	@echo "  make shell-php   - Enter PHP container"
	@echo "  make shell-node  - Enter Node container"
	@echo ""
	@echo "Setup:"
	@echo "  make install     - Install all dependencies"
	@echo "  make migrate     - Run migrations"
	@echo "  make seed        - Run seeders"
	@echo ""
	@echo "Testing:"
	@echo "  make test            - Run all tests"
	@echo "  make test-feature    - Run Feature tests"
	@echo "  make test-unit       - Run Unit tests"
	@echo "  make test-auth       - Run Auth tests"
	@echo "  make test-publisher  - Run Publisher tests"
	@echo "  make test-admin      - Run Admin tests"
	@echo "  make test-coverage   - Run tests with coverage"
	@echo "  make test-parallel   - Run tests in parallel"
	@echo ""
	@echo "Queue:"
	@echo "  make queue-work      - Start queue worker"
	@echo "  make queue-listen    - Start queue listener (auto-reload)"
	@echo "  make queue-restart   - Restart queue workers"
	@echo "  make queue-failed    - Show failed jobs"
	@echo "  make queue-retry     - Retry all failed jobs"
	@echo ""
	@echo "Cleanup:"
	@echo "  make clean       - Clean all data and volumes"

up:
	docker-compose up -d

down:
	docker-compose down

restart:
	docker-compose restart

build:
	docker-compose up -d --build

logs:
	docker-compose logs -f

shell-php:
	docker exec -it myads_php sh

shell-node:
	docker exec -it myads_node sh

install:
	@echo "Installing backend dependencies..."
	docker exec myads_php composer install
	docker exec myads_php php artisan key:generate
	@echo "Installing frontend dependencies..."
	docker exec myads_node npm install
	@echo "Done!"

migrate:
	docker exec myads_php php artisan migrate

seed:
	docker exec myads_php php artisan db:seed

test:
	@echo "Running all tests..."
	docker exec myads_php php artisan test

test-feature:
	@echo "Running Feature tests..."
	docker exec myads_php php artisan test --testsuite=Feature

test-unit:
	@echo "Running Unit tests..."
	docker exec myads_php php artisan test --testsuite=Unit

test-auth:
	@echo "Running Auth tests..."
	docker exec myads_php php artisan test --filter Auth

test-publisher:
	@echo "Running Publisher tests..."
	docker exec myads_php php artisan test --filter Publisher

test-admin:
	@echo "Running Admin tests..."
	docker exec myads_php php artisan test --filter Admin

test-coverage:
	@echo "Running tests with coverage..."
	docker exec myads_php php artisan test --coverage

test-parallel:
	@echo "Running tests in parallel..."
	docker exec myads_php php artisan test --parallel

queue-work:
	@echo "Starting queue worker..."
	docker exec -it myads_php php artisan queue:work --verbose --tries=3 --timeout=90

queue-listen:
	@echo "Starting queue listener (auto-reload)..."
	docker exec -it myads_php php artisan queue:listen --verbose --tries=3 --timeout=90

queue-restart:
	@echo "Restarting queue workers..."
	docker exec myads_php php artisan queue:restart

queue-failed:
	@echo "Showing failed jobs..."
	docker exec myads_php php artisan queue:failed

queue-retry:
	@echo "Retrying all failed jobs..."
	docker exec myads_php php artisan queue:retry all

clean:
	docker-compose down -v
	rm -rf backend/vendor
	rm -rf frontend/node_modules
	@echo "Cleaned!"
