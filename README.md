# Installation Instructions

## Start Docker

```
docker compose up -d
```

## Install dependencies

```
composer install
```

## Set database

Navigate to the Symfony directory / container

Then :
```
php bin/console make:migration
```
```
php bin/console d:m:m
```

## Access to the pages

- phpMyAdmin : localhost:8070
- Symfony : localhost:8080

### Finished !
