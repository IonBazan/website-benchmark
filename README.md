# Website Benchmark

This application compares your website to some competitors and notifies you when your website is really slow.

## Installation
Make sure you have latest Docker and Docker-Compose binaries.
```bash
cp .env.dist .env
cp docker/.env.dist docker/.env
cd docker
docker-compose up -d
```
And you are ready to go!

## Execution
The `app` container does not have any command to run so it's closing as soon as created.
You can provide him a command or use `run` syntax:
```bash
docker-compose run app bash
composer install
bin/console app:benchmark --help
```

## Customization
You can easily customize app behavior by setting the SMS/E-mail conditions and playing with DI config.

## Running tests
Tests are being run using Symfony PHPUnit Bridge:
```bash
bin/phpunit
```

## Next steps:
 - Add real SMS API implementation
 - Create a Web-App
 - Add more Reporters
 - More tests!
 - More elegant output and logging
