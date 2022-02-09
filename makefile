docker-develop-up:
	docker-compose -f docker-compose.develop.yml up --build

docker-develop-down:
	docker-compose -f docker-compose.develop.yml down

docker-up:
	docker-compose up --build

docker-down:
	docker-compose down