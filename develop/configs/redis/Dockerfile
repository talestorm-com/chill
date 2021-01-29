FROM redis:4-alpine

EXPOSE 6379
EXPOSE 10000

COPY redis.conf /etc/redis/redis.conf

RUN apk add --update --no-cache tzdata && \
    cp /usr/share/zoneinfo/UTC /etc/localtime && \
    echo "UTC" >  /etc/timezone && \
    mkdir -p /var/log/redis && \
    chown -R redis:redis /var/log/redis && \
    chmod +x /usr/bin/start.sh

