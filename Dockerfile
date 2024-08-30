FROM alpine:3.20

ENV TZ=UTC \
	WORKDIR=/var/www/ \
	USER=blog \
	GROUP=blog \
	SUPERV=/etc/supervisor.d/

WORKDIR ${WORKDIR}

COPY --chown=${USER}:${GROUP} ./ ${WORKDIR}

COPY .build/app.conf /etc/nginx/conf.d/default.conf
COPY .build/www.conf /etc/php83/php-fpm.d/www.conf
COPY .build/nginx.conf /etc/nginx/nginx.conf
COPY .build/repositories /etc/apk/repositories
COPY .build/supervisord.conf /etc/supervisord.conf

RUN apk update && apk upgrade --no-cache --no-progress && apk add \
	php83 php83-fpm php83-bcmath php83-bz2 php83-calendar php83-cgi php83-common php83-ctype \
	php83-curl php83-dba php83-dev php83-doc php83-dom php83-embed \
	php83-enchant php83-exif php83-fileinfo php83-fpm php83-ftp php83-gd \
	php83-gettext php83-gmp php83-iconv php83-imap php83-intl php83-json \
	php83-ldap php83-litespeed php83-mbstring php83-mysqli php83-mysqlnd \
	php83-odbc php83-opcache php83-openssl php83-pcntl php83-pdo php83-pdo_dblib \
	php83-pdo_mysql php83-pdo_odbc php83-pdo_pgsql php83-pdo_sqlite php83-pear \
	php83-pgsql php83-phar php83-phpdbg php83-posix php83-pspell \
	php83-session php83-shmop php83-simplexml php83-snmp php83-soap php83-sockets \
	php83-sodium php83-sqlite3 php83-sysvmsg php83-sysvsem php83-sysvshm \
	php83-tidy php83-tokenizer php83-xml php83-xmlreader \
	php83-xmlwriter php83-xsl php83-zip php83-pecl-xhprof php83-pecl-xhprof-assets \
	php83-pecl-memcached php83-pecl-igbinary \
	php83-pecl-ssh2 php83-pecl-imagick php83-pecl-imagick-dev \
	php83-pecl-ast php83-pecl-redis php83-pecl-apcu \
	php83-pecl-msgpack php83-pecl-yaml php83-brotli php83-pecl-amqp \
	py3-pip curl tzdata libjpeg-turbo-dev libjpeg-turbo oniguruma oniguruma-dev icu-data-full nginx nginx-mod-http-headers-more zip libcap git supervisor \
	--no-cache --no-progress && \
	\
	ln -s /usr/sbin/php-fpm83 /usr/bin/php-fpm && \
	\
	PHP_VERSION=$(php -r 'echo PHP_VERSION;') && \
	curl https://raw.githubusercontent.com/php/php-src/php-${PHP_VERSION}/php.ini-production --output php.ini-production && \
	curl https://raw.githubusercontent.com/php/php-src/php-${PHP_VERSION}/php.ini-development --output php.ini-development && \
	mv php.ini-production /etc/php83 && mv php.ini-development /etc/php83 && \
	\
	cp /usr/share/zoneinfo/${TZ} /etc/localtime && \
	\
	mkdir -p /run/nginx ${SUPERV} && \
	\
	setcap 'cap_net_bind_service=+ep' /usr/sbin/nginx && \
	\
	addgroup -g 1000 ${GROUP} && \
	adduser -G ${GROUP} -H -D -s /sbin/nologin -u 1000 ${USER} && \
	\
	chmod 770 -R ${WORKDIR} && \
	\
	chown -R ${USER}:${GROUP} ${WORKDIR} ${SUPERV} /run /var

COPY .build/php-ini-overrides.ini /etc/php83/conf.d/99-overrides.ini
COPY .build/security.ini /etc/php83/conf.d/42-security-dont-remove.ini
#COPY .build/opcache.ini /etc/php83/conf.d/90-opcache.ini

EXPOSE 80

USER ${USER}

CMD ["supervisord", "-c", "/etc/supervisord.conf", "-s"]
