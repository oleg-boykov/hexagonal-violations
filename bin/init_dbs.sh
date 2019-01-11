#!/usr/bin/env bash

DATA_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )"/../ && pwd )"/data
cat ${DATA_DIR}/data.sql | docker exec -i $(docker-compose ps -q db) mysql -uroot -proot api
cat ${DATA_DIR}/data.sql | docker exec -i $(docker-compose ps -q db) mysql -uroot -proot api_test
