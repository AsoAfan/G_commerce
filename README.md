<p align="center">
<a href="https://www.gigant.tech" target="_blank">
<img src="./public/Img/GigantLogo.png" height="200" alt="Gigants Logo">
<h1 align="center">Gigant Tech</h1>
</a>

# G_Commerce

This project made as an intern project, it is about an ecommerce app

### Project Setup

---
install dependencies

```shell
composer update && npm install
```

set env file

```shell
cp .env.example .env
```

### Generate app key

---

```shell
php artisan key:generate
```

### Database setup

---
fill up database credentials in `.env` and then migrate the database

```shell
php artisan migrate
```

### Start Laravel server

---

```shell
php artisan serve 
```

#### REST API:

- base url: [http://127.0.0.1:8000/api](http://127.0.0.1:8000/api)
- docs: [http://127.0.0.1:8000](http://127.0.0.1:8000/)

#### GraphQL API <sub>(not available yet)</sub>

- base url: [http://127.0.0.1:8000/graphql](http://127.0.0.1:8000/graphql)
- docs: [http://127.0.0.1:8000/garphiql](http://127.0.0.1:8000/graphiql)

---

Happy coding 🥰

&copy; 2024 by [Gigant Technology](https://www.gigant.tech/), inc.
