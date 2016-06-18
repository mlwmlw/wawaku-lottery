FROM php:5.6-apache
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN a2enmod rewrite
RUN apt-get update
RUN apt-get install -y git-core
RUN rm composer-setup.php
RUN mv composer.phar /usr/local/bin/composer
