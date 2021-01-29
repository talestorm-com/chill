#!/bin/sh
container_prefix="chill-backend"

DB_COMMAND=$(cat <<-END
    mysql -hlocalhost -uroot -p123 -e "
    CREATE DATABASE IF NOT EXISTS chill CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    use chill;";
END
)

docker exec $container_prefix"-mysql" sh -c "$DB_COMMAND";
