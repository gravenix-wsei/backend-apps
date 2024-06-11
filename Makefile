include .env

help :
	printf  "\
[=========== \e[0;33mProgramowanie aplikacji backendowych - projekt zaliczeniowy\e[0m ===========]\n\
Available commands: \n\
  \e[0;32mhelp\e[0m - displays help \n\
  \e[0;32m.env\e[0m - generate .env file \n\
  \e[0;32mcheck\e[0m - checks if required executables are installed to run this project \n\
  \e[0;32minit\e[0m - initializes and starts the application \n\
  \e[0;32mbuild\e[0m - builds docker images \n\
  \e[0;32mstart\e[0m - starts docker images \n\
  \e[0;32mstop\e[0m - stops docker images \n\
  \e[0;32mcomposer-cli\e[0m - runs conatainer for app to use composer commands \n\
  \e[0;32mapp-cli\e[0m - enter app cli \n\
  \e[0;32mapp-cli\e[0m - enter post microservice cli \n\
  \e[0;32mdatabase-cli\e[0m - enter database mysql cli as root \n\
  \e[0;32mlogs\e[0m - displays logs from docker containers \n\
"


.check-silent :
	bash -c "${PWD}/scripts/check-project-requirements.sh -s"

init : .check-silent .env build start
	docker compose up -d
	docker compose exec -it app bin/setup.sh

check:
	bash -c "${PWD}/scripts/check-project-requirements.sh -v"

.env :
	if [ ! -f ".env" ]; then \
    	cp ".env.dist" ".env"; \
  		echo ".env created"; \
	fi

build :
	docker compose build

start :
	docker compose start

stop :
	docker compose stop

composer-cli :
	docker run --rm -it --volume="./app:/app" --volume="./post-service:/post-service" "composer/composer:${COMPOSER_VERSION}" bash

app-cli : start
	docker compose exec -it --workdir /app --user "www-data" app bash

post-service-cli: start
	docker compose exec -it --workdir /app --user "www-data" post-service bash

database-cli : start
	docker compose exec -it --user mysql database bash -c "mysql -h localhost -u root -p'${MYSQL_ROOT_PASSWORD}'"

logs : start
	docker compose logs --follow --tail 300

.PHONY: help .check-silent check init build start stop composer-cli database-cli logs post-service-cli