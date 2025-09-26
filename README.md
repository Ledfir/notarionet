# Laravel 8 and Vue.js

Es una actualización del anterior proyecto [Laravel-vue](https://bitbucket.org/sustambackend/laravel-vue).
Debido al cambio de versión de laravel, recomiendo leer la [documentación de laravel 8](https://laravel.com/docs/8.x) Ya que si hay cambios notables por ejemplo formato de routers.
## Adios a jwt-auth. 
Ahora se esta utilizando [Laravel Sanctum](https://laravel.com/docs/8.x/sanctum#spa-authentication). con el metodo SPA Authentication. Para esta función, Sanctum no usa tokens de ningún tipo. En cambio, Sanctum utiliza los servicios de autenticación de sesión basados ​​en cookies incorporados de Laravel.

### Requisitos

Para usar este proyecto es necesario lo siguiente:

```
* PHP version >= 7.3
* Componser
* NodeJS (recomendado version >= 14.15) 
```

### Instalación

Lo primero que necesitamos para instalar el proyecto es instalar las librerias PHP con Composer.

Ejecuta en terminal:

```
composer install
```
Despues descargamos los paquetes necesarios para vuejs y compilamos los archivos
```
npm install
```
```
npm run dev
```
Por ultimo es necesario crear las tablas y sembrar los datos en la base de datos 
```
php artisan migrate --seed
```
Con esto ya estaria instalado el proyecto.

## NOTA 
No olvides antes agregarle la información de la base de datos al archivo .env 




## Authors
* **Ramón Plascencia** - *Trabajo inicial* - [ramon-sustam](https://bitbucket.org/ramon-sustam)


## License
las licencias de [Laravel](https://laravel.com/) y [Vue.JS](https://vuejs.org/).

