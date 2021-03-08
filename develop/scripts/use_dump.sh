#!/bin/sh
container_prefix="chill-backend"

DB_COMMAND=$(cat <<-END
    mysql -uroot -hlocalhost -p123 chill < dump/chill.sql
END
)

docker exec $container_prefix"-mysql" sh -c "$DB_COMMAND";
