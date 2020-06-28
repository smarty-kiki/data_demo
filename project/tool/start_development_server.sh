#!/bin/bash

ROOT_DIR="$(cd "$(dirname $0)" && pwd)"/../..

sh $ROOT_DIR/project/tool/dep_build.sh link

sudo docker run --rm -ti -p 80:80 -p 8080:8080 -p 3306:3306 --name data_demo \
    -v $ROOT_DIR/../frame:/var/www/frame \
    -v $ROOT_DIR/:/var/www/data_demo \
    -v $ROOT_DIR/project/config/development/nginx/data_demo.conf:/etc/nginx/sites-enabled/default \
    -v $ROOT_DIR/project/config/development/supervisor/data_demo_queue_worker.conf:/etc/supervisor/conf.d/data_demo_queue_worker.conf \
    -e 'PRJ_HOME=/var/www/data_demo' \
    -e 'ENV=development' \
    -e 'TIMEZONE=Asia/Shanghai' \
    -e 'AFTER_START_SHELL=/var/www/data_demo/project/tool/development/after_env_start.sh' \
kikiyao/debian_php_dev_env start
