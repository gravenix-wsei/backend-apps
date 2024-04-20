.SILENT:
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
"

.check-silent :
	bash -c "${PWD}/scripts/check-project-requirements.sh -s"

init : .check-silent .env build start
	docker compose up -d

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

.PHONY: help .check-silent check init build start stop