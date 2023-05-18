# Utilisez l'image officielle de PHP 8.1 comme image de base
FROM php:8.1

# Installez les dépendances de PHP nécessaires à Laravel
RUN docker-php-ext-install pdo_mysql

# Copiez les fichiers de l'application Laravel dans le conteneur
COPY . /var/www/html

# Définissez le répertoire de travail actif
WORKDIR /var/www/html

# Lancez le serveur intégré de PHP pour exécuter l'application Laravel
CMD ["php", "artisan", "serve"]