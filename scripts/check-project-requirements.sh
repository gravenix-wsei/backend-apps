#!/usr/bin/env bash

EXEC_MISSING=0
SILENT_RUN=
VERBOSE=

while getopts "vs" flag; do
  case $flag in
  s)
    SILENT_RUN=1
    ;;
  v)
    VERBOSE=1
    ;;
  ?)
    echo "Invalid option '${flag}'. Available options are: -v, -s"
    exit 1
    ;;
  esac
done;

DOCKER_VERSION=$(docker --version 2> /dev/null)
DOCKER_COMPOSE_VERSION=$(docker compose version 2> /dev/null)

function logMissingExecutable() {
  printf "\e[0;31mMissing executable \e[0;33m\`%s\`\e[0m. Please install it to use this project\n" "${1}"
}

function displayVersion() {
  printf "\e[0;33m%s\e[0m: %s\n" "${1}" "${2}"
}

if [ -z "${DOCKER_VERSION}" ]; then
  EXEC_MISSING=1
  logMissingExecutable "docker"
elif [[ $VERBOSE -gt 0 ]]; then
  displayVersion "docker" "${DOCKER_VERSION}"
fi

if [ -z "${DOCKER_COMPOSE_VERSION}" ]; then
  EXEC_MISSING=1
  logMissingExecutable "docker compose"
elif [[ $VERBOSE -gt 0 ]]; then
  displayVersion "docker compose" "${DOCKER_COMPOSE_VERSION}"
fi

if [[ ! $SILENT_RUN -eq 1 ]]; then
  if [[ $EXEC_MISSING -eq 0 ]]; then
    printf "\e[0;32mAll required executables are installed. You're good to go.\e[0m\n"
  else
    printf "\nTo use this project please resolve all problems listed above.\n"
  fi
fi