<p align="center"><a href="https://www.gigant.tech" target="_blank"><img src="https://drive.google.com/uc?export=view&id=1W0gAjU3Z_u2-34cAODGGmwXjNky1dSc3" height="200" alt="Gigants Logo"></a></p>


[//]: # (<p align="center">)

[//]: # (<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>)

[//]: # (<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>)

[//]: # (<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>)

[//]: # (<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>)

[//]: # (</p>)


# G_Commerce 
This project made as an intern project, it is about an ecommerce app


### Project Setup

---
install dependencies
```
composer update && npm install
```

duplicate `.env.example` and rename duplicated to `.env`


### Generate app key

---

```
php artisan key:generate
```
### Database setup

---
fill up database credentials in `.env` and then migrate the database
```
php artisan migrate
```

### Run backend server

---

```
php artisan serve 
```

#### REST API:
- base url: [http://127.0.0.1:8000/api](http://127.0.0.1:8000/api)
- docs: [http://127.0.0.1:8000/docs](http://127.0.0.1:8000/docs)
#### GraphQL URL
- base url and docs: [http://127.0.0.1:8000/garphiql](http://127.0.0.1:8000/graphiql) (Developing...)
    
---
Happy coding 🥰


Copyright © 2024 by [Gigant Technology](https://www.gigant.tech), inc. All rights reserved.

