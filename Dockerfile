# Usa una imagen base de PHP con Apache
FROM php:8.1-apache

# Instala extensiones de PHP necesarias (puedes agregar más si tu aplicación lo requiere)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copia los archivos de la aplicación en la carpeta de Apache
COPY . /var/www/html/

# Otorga permisos si es necesario
RUN chown -R www-data:www-data /var/www/html