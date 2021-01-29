#!/usr/bin/env sh

if [ "$REDIS_PASSWORD" ]; then
    echo "requirepass $REDIS_PASSWORD" >> /etc/redis/redis.conf
fi

/usr/local/bin/redis-server /etc/redis/redis.conf
