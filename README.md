# SMS Gateway

## How to deploy on production

[laravel deployment document](https://laravel.com/docs/9.x/deployment)


## Requirements

* php version 8.1
* composer installed
* docker

## PHP required extensions

* Curl PHP Extension
* JSON PHP Extension
* Mbstring PHP Extension
* XML PHP Extension
* Mongodb PHP Extension (important)
* Redis PHP Extension

## Installation

```bash
git clone https://github.com/hamidroohani/SMS-Gateway.git
```

```bash
cd SMS-Gateway && cp .env.example .env
```

Install other services with docker compose
```bash
cd docker && sudo docker-compose up -d
```

Install composer
```bash
cd .. && composer install
```

## Create user mongodb

connect to docker mongodb
```
sudo docker exec -it [hash-code-from-mongo] bash
```

```
mongo -u root
```

```
use sms-gateway
```

```
db.createUser(
{
 user: "user-sms-gateway",
 pwd:  "97v4MUt8s25G1",
 roles:
    [
        {
            role:"readWrite",
            db:"sms-gateway"
        },
    ]
});
```

Also create another database for test
```
use test-sms-gateway
```

```
db.createUser(
{
 user: "user-sms-gateway",
 pwd:  "97v4MUt8s25G1",
 roles:
    [
        {
            role:"readWrite",
            db:"test-sms-gateway"
        },
    ]
});
```

exit from docker container and run:
```bash
php artisan key:generate
```

## Create database collections
```
php artisan migrate
```

## Check the project health
```
php artisan test
```
`If you're faced with any errors in test, try to find the problems, most of all it happens of connection between services`

## Complete the provider basic information
```
nano database/seeders/ProviderSeeder.php
```
After that run the seeder
```
php artisan db:seed
```

## Ask devops to run this command on server and keep it alive
```
cd /path-to-your-project && php artisan send:sms
```

## Run local server
```
php artisan serve
```


# Send a message

This is an example of a `POST` request API for send a message.

## Sample:

````
POST /api/messages/send
Accept: application/json

{
    "body": "salam",
    "receiver_mobile": "09375775947"
}
````
## Response:

````
{
    "status": true,
    "data": {
        "track_id": "62aff1e9424f29b97e0a61a3"
    }
}
````

You can use the track id for next Api.


# Track a sent message

This is an example of a `POST` request API for track a message that sent before.

## Sample:

````
POST /api/messages/track/{track_id}
Accept: application/json
````
## Response:

````
{
    "status": true,
    "data": {
        "status": "pending"
    }
}
````


Also, you can use the `postman_file` in the root.
