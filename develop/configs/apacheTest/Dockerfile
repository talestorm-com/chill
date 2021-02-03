FROM ubuntu:16.04

# update
RUN apt-get update

# install apache
RUN apt-get install -y apache2 libapache2-mod-rpaf \
    && a2enmod rewrite \
    && a2enmod rpaf \
    && a2enmod proxy \
    && a2enmod proxy_http \
    && a2enmod headers \
    && a2enmod remoteip

COPY apache2-foreground /usr/bin/
WORKDIR /data

# set time zone
ENV TZ=Asia/Novosibirsk
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

EXPOSE 80
CMD ["apache2-foreground"]