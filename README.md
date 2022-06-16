# SMS Gateway

## Install service

```bash
cd docker && docker-compose up -d
```

## Install Notifier

```bash
composer update
```

## Create user mongodb

connect to docker mongodb
```
sudo docker exec -it [hash code] bash
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

## Create database tables
```
php artisan migrate
```

## Set laravel schedule on server crontab
```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## Run local server
```
php artisan serve
```
