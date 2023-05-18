# Utilisez l'image officielle de PHP 8.1 comme image de base avec Apache
FROM php:8.1-apache

# Activez le module Apache mod_rewrite
RUN a2enmod rewrite

# Librairies nécessaires à l'installation de Laravel
RUN apt-get update && apt-get install -y \
    libmariadb-dev

# Installer composer depuis l'image officielle de composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installez les dépendances de PHP nécessaires à Laravel pour communiquer avec MySQL
RUN docker-php-ext-install pdo_mysql

# Définissez le répertoire racine de l'application Laravel
WORKDIR /app

# Copiez les fichiers de l'application Laravel à la racine du conteneur
COPY . .

# Lancez le serveur intégré de PHP pour exécuter l'application Laravel
CMD ["php", "artisan", "serve"]