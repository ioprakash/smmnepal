FROM php:8.2-apache

ENV APACHE_DOCUMENT_ROOT=/var/www/html

RUN set -eux; \
	apt-get update; \
	apt-get install -y --no-install-recommends \
		ca-certificates \
		curl \
		unzip \
		libzip-dev \
		libonig-dev \
		libicu-dev \
	; \
	docker-php-ext-install -j"$(nproc)" pdo_mysql mbstring zip intl; \
	a2enmod rewrite headers expires; \
	rm -rf /var/lib/apt/lists/*

# Allow .htaccess overrides (required by many panels for routing/static caching rules)
RUN set -eux; \
	sed -ri 's/AllowOverride\s+None/AllowOverride All/g' /etc/apache2/apache2.conf

WORKDIR /var/www/html
COPY . /var/www/html

# Writable dirs used by the app (logs/cache/sessions/uploads)
RUN set -eux; \
	mkdir -p storage/logs storage/cache storage/sessions public/uploads public/cache; \
	chown -R www-data:www-data storage public/uploads public/cache

COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
	CMD curl -fsS http://localhost/ >/dev/null || exit 1

ENTRYPOINT ["/entrypoint.sh"]
CMD ["apache2-foreground"]

