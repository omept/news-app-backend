# Dockerized Laravel NewsFeed API with Authentication, Multiple Aggregators, Search, Country And Category Filter, And Tests.

This projects returns configurable news feeds from:
```
- NewscatcherApi.com
- NewsData.io
- NewsApi.org
```

It is loaded with, Docker, Linux, MySQL, Redis, MailHog, NGINX, PHP, Composer, and Artisan network of containers for it, hence making usage easy.



# Set-up with Docker

Follow the instructions below to run the application

## Usage

To get started, make sure you have [Docker installed](https://docs.docker.com/docker-for-mac/install/) on your system, and then clone this repository.

Next, navigate in your terminal to the directory you cloned this, and spin up the containers for the web server by running

-   `docker-compose up -d --build app`

Next, run the following command only once

-   `docker-compose run --rm composer update`
-   `docker-compose run --rm composer dump-autoload`
-   `docker-compose run --rm artisan cache:clear`
-   `docker-compose run --rm artisan config:clear`
-   `docker-compose run --rm artisan app:key`
-   `docker-compose run --rm artisan jwt:secret`
-   `docker-compose run --rm artisan migrate`
-   `docker-compose run --rm artisan db:seed`

Next, open browser and visit:

-   `localhost`

The following are built for our web server, with their exposed ports detailed:

-   **nginx** - `:80`
-   **mysql** - `:3306`
-   **php** - `:9000`
-   **redis** - `:6379`
-   **mailhog** - `:8025`


---

## Aggregator Requirements
Update the following varaibles in `.env` with appropriate keys
```
NEWS_API_KEY=
NEWS_DATA_API_KEY=
NEWS_CATCHER_API_KEY=
```
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
