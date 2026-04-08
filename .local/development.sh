#!/bin/bash

GREEN='\033[0;32m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}🚀 Starting Leaflly development environment...${NC}\n"

cd "$(dirname "$0")"

echo -e "${GREEN}📦 Building and running Docker containers...${NC}"
docker-compose up -d --build

if [ ! -f ../.env ]; then
    echo -e "${GREEN}📝 Creating .env file based on .env.example...${NC}"
    cp ../.env.example ../.env
fi

docker exec -it leaflly-app git config --global --add safe.directory /var/www

echo -e "${GREEN}📚 Installing composer packages...${NC}"
docker exec -it leaflly-app composer install

echo -e "${GREEN}🔑 Generating App key...${NC}"
docker exec -it leaflly-app php artisan key:generate

echo -e "${GREEN}🗄️ Running database migrations...${NC}"
docker exec -it leaflly-app php artisan migrate --seed


echo -e "${GREEN}📝 Creating API documentation...${NC}"
docker exec -it leaflly-app php artisan scribe:generate --no-interaction

echo -e "\n${BLUE}✅ Ready to go! App works on: http://localhost:8080${NC}"
echo -e "To access container, type: docker exec -it leaflly-app bash"
