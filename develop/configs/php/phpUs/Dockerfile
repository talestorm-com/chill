FROM ubuntu:18.04

# add php7.2 repo
#RUN export LC_ALL="en_US.UTF-8" && \
#    add-apt-repository -y ppa:ondrej/php && \
#    apt-get update

ENV DEBIAN_FRONTEND=noninteractive

# update and install deps
RUN apt-get update && \
    apt-get install -y software-properties-common locales ca-certificates && \
    locale-gen en_US.UTF-8 ru_RU.UTF-8 && \
    apt-get install -y zlib1g-dev libssl-dev pkg-config libmagickwand-dev libmagickcore-dev git

# install php
RUN apt-get install -y \
  php7.2-bcmath \
  php7.2-cli \
  php7.2-common \
  php7.2-curl \
  php7.2-dev \
  php7.2-gd \
  php7.2-imap \
  php7.2-json \
  php7.2-intl \
  php7.2-ldap \
  php7.2-mbstring \
  php7.2-mysql \
  php7.2-opcache \
  php7.2-pgsql \
  php7.2-readline \
  php7.2-soap \
  php7.2-sqlite3 \
  php7.2-xml \
  php7.2-yaml \
  php7.2-zip \
  php-memcache \
  php-pear \
  php-redis

# php-mcrypt php-imagick php-mongodb php-dbase php-memcached
RUN apt install -y libmcrypt4 libmcrypt-dev mcrypt libmemcached11 libmemcached-dev && \
    pecl install channel://pecl.php.net/mcrypt-1.0.1 && \
    pecl install memcached && \
    pecl install imagick && \
    pecl install mongodb && \
    pecl install dbase-7.0.0beta1 && \
    echo "extension=memcached.so" > /etc/php/7.2/mods-available/memcached.ini && \
    echo "extension=mcrypt.so" > /etc/php/7.2/mods-available/mcrypt.ini && \
    echo "extension=imagick.so" > /etc/php/7.2/mods-available/imagick.ini && \
    echo "extension=mongodb.so" > /etc/php/7.2/mods-available/mongodb.ini && \
    echo "extension=dbase.so" > /etc/php/7.2/mods-available/dbase.ini && \
    phpenmod memcached && \
    phpenmod mcrypt && \
    phpenmod imagick && \
    phpenmod mongodb && \
    phpenmod dbase

## mailparse
#RUN pecl download mailparse && \
#    tar -xvf mailparse-3.0.3.tgz  && \
#    cd mailparse-3.0.3/  && \
#    phpize  && \
#    ./configure  && \
#    sed -i 's/#if\s!HAVE_MBSTRING/#ifndef MBFL_MBFILTER_H/' ./mailparse.c  && \
#    make  && \
#    make install  && \
#    echo "extension=mailparse.so" > /etc/php/7.2/mods-available/mailparse.ini && \
#    phpenmod mailparse && \
#    echo "date.timezone = UTC" >> /etc/php/7.2/cli/php.ini

WORKDIR /tmp

# usefull libs
RUN apt-get install -y netcat wget curl iputils-ping telnet rsync

# xdiff
RUN apt-get install -y re2c && \
    wget http://www.xmailserver.org/libxdiff-0.23.tar.gz && \
    tar -xzf libxdiff-0.23.tar.gz && \
    cd libxdiff-0.23 && \
    ./configure && \
    make && \
    make install && \
    pecl install xdiff && \
    echo "extension=xdiff.so" > /etc/php/7.2/mods-available/xdiff.ini && \
    phpenmod xdiff

WORKDIR /data

# utils
RUN apt-get install -y mc p7zip-full zip
ENV TERM=xterm

# cron
RUN apt-get install -y cron && \
    rm -rf /etc/cron.d/* && \
    rm -rf /etc/cron.daily/* && \
    rm -rf /etc/cron.hourly/* && \
    rm -rf /etc/cron.monthly/* && \
    rm -rf /etc/cron.weekly/* && \
    rm -rf /etc/pam.d/cron


# update
RUN apt-get update

# install apache
RUN apt-get install -y apache2 libapache2-mod-rpaf libapache2-mod-php7.2 \
    && a2enmod rewrite \
    && a2enmod rpaf \
    && a2enmod proxy \
    && a2enmod proxy_http \
    && a2enmod headers \
    && a2enmod remoteip

# php enable mods
RUN phpenmod memcached && \
    phpenmod mongodb && \
    phpenmod dbase && \
    phpenmod mcrypt && \
    phpenmod imagick && \
    phpenmod mailparse

# configure php
RUN echo "date.timezone = UTC" >> /etc/php/7.2/apache2/php.ini

COPY apache2-foreground /usr/bin/
WORKDIR /data

EXPOSE 80
CMD ["apache2-foreground"]


# update
RUN apt-get update && \
    apt-get install -y mysql-client ffmpeg ssmtp unoconv sudo && \
    # unoconv hack
    chown -R www-data:www-data /var/www && \
    sudo -u www-data unoconv -f pdf foo >/dev/null 2>&1 || true && \
    # imagemagick hack
    sed -i 's/domain="coder" rights="none" pattern="PDF"/domain="coder" rights="read|write" pattern="PDF"/' /etc/ImageMagick-6/policy.xml

# set time zone
ENV TZ=Asia/Novosibirsk
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

WORKDIR /data

COPY start.sh /usr/bin/

COPY sudoers /etc/sudoers

CMD ["start.sh"]


# xdebug
RUN pecl install xdebug && \
    echo "zend_extension=xdebug.so" > /etc/php/7.2/mods-available/xdebug.ini && \
    phpenmod xdebug

# xhprof
WORKDIR /

RUN git clone https://github.com/phacility/xhprof.git && \
    git clone https://github.com/tideways/php-xhprof-extension.git && \
    cd php-xhprof-extension && \
    phpize && \
    ./configure --with-php-config=/usr/bin/php-config && \
    make && \
    make install && \
    echo "extension=tideways_xhprof.so\nxhprof.output_dir=/tmp/xhprof" >> /etc/php/7.2/mods-available/xhprof.ini && \
    ln -s /etc/php/7.2/mods-available/xhprof.ini /etc/php/7.2/cli/conf.d/10-xhprof.ini && \
    ln -s /etc/php/7.2/mods-available/xhprof.ini /etc/php/7.2/apache2/conf.d/10-xhprof.ini && \
    chown -R www-data:www-data /xhprof && \
    apt-get update && \
    apt-get install -y graphviz

COPY sudoers /etc/sudoers

WORKDIR /data