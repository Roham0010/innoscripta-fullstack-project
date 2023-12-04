start:
	docker compose up --build -d
	docker exec -it innoscripta-fullstack-project-backend-1 php artisan key:generate
	docker exec -it innoscripta-fullstack-project-backend-1 php artisan migrate
	docker exec -it innoscripta-fullstack-project-backend-1 php artisan get:articles
