# Dockerized Laravel News App API with Authentication, Multiple Aggregators, Search, Country And Category Filter, And Tests.

This projects has a Docker Compose workflow that sets up Linux, MySQL, PHPAdmin, NGINX, PHP 7.4, Composer, Artisan, and XDebug network of containers for it, hence making usage easy.  
# Set-up with Docker 
Follow the instructions below to run the application

## Use

To get started, make sure you have [Docker installed](https://docs.docker.com/) on your system and [Docker Compose](https://docs.docker.com/compose/install/), and then clone this repository.


1. Inside the folder, generate your own `.env` :

   ```sh
   cp .env.example .env
   ```

2. Build the project whit the next commands:

   ```sh
   docker-compose up -d --build
   ```

---

## REST endpoints
```bash
App endpoints can be found in api.rest file

#### Routes ⚡
| Routes           | HTTP Methods | Params                                   | Description                                                                                                  |
| :--------------- | :----------- | :--------------------------------------- | :----------------------------------------------------------------------------------------------------------- |
| /                | GET          | none                                     | Displays application infomation                                                                              |
| /login           | POST         | `email` `password`                       | Logs in a user and returns the jwt session token                                                             |
| /sign-up         | POST         | `email`,`password`, `name`               | Registers a user                                                                                             |
| /refresh         | POST         | none                                     | Refresh a user jwt token                                                                                     |
| /invalidate      | POST         | none                                     | Invalidate a user jwt token                                                                                  |
| /settings        | POST         | `country`, `category`, `provider`        | Update user preference                                                                                       |
| /meta            | GET          | `none`                                   | Returns default data used in app (e.g List of Providers/Categories                                           |
| /feeds           | GET          | `country`, `category`, `search`         | News feed                                                                                                     |

```

---

## Ports

Ports used in the project:
| Software | Port |
|-------------- | -------------- |
| **nginx** | 8080 |
| **phpmyadmin** | 8081 |
| **mysql** | 3306 |
| **php** | 9000 |
| **xdebug** | 9001 |


## Special Cases

To Down and remove the volumes we use the next command:

```sh
docker-compose down -v
```

Update Composer:

```sh
docker-compose run --rm composer update
```

Run compiler (Webpack.mix.js) or Show the view compiler in node:

```sh
docker-compose run --rm npm run dev
```

Run all migrations:

```sh
docker-compose run --rm artisan migrate
```

Run all seeds:

```sh
docker-compose run --rm artisan db:seed
```

