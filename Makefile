.PHONY: deploy up down restart logs ps

deploy:
	chmod +x scripts/oneclick-deploy.sh
	./scripts/oneclick-deploy.sh

up:
	COMPOSE_BAKE=false sudo docker compose up -d --build

down:
	sudo docker compose down

restart:
	sudo docker compose restart

logs:
	sudo docker compose logs -f --tail=200 web

ps:
	sudo docker compose ps
