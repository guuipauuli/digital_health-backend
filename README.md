# Digital Health API

The Digital Health application is a tool to be used by doctors, speech therapists, 
psychologists and many others healthcare sectors.
Throught it, they can manage theirs financial, accounting, tax, appointment schedule.

Requirements
------------

* PHP 8.1 or higher;
* Composer
* [The framework doc][1]

Installation
------------

You must provide a composer and run:

There's no need to configure anything before running the application. There are
2 different ways of running this application depending on your needs:

Every way you will need to clone the repository:

```bash
$ git clone https://github.com/{$repository}/digital_health-backend.git
```

**Option 1.** Running locally:

```bash
$ cd my_project/src/
$ composer install
$ php -S localhost:8000 -t public
```
Then access the application in your browser at the given URL (<https://localhost:8000> by default).

**Option 2.** Running throught the docker:

On this way, you will need the docker installed

```bash
$ cd my_project/
$ docker compose up -d
```

[1]: https://symfony.com/doc
