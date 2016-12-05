# Frameworkless
An admittedly overly simplistic example of combining a few popular packages into your own micro-framework.


## Why?

**Education.**

I do not recommend building your own framework unless you have a very compelling reason to do so.
Instead,use a popular & well-supported framework like [Symfony](http://symfony.com), [Slim](https://www.slimframework.com), or [Laravel](http://laravel.com).

### What's included?
I spent some time picking out packages, preferring those used by existing large applications or frameworks. Here is what's included, in no particular order:

* **[nikic/fast-route](https://github.com/nikic/FastRoute)**
  * Popular routing library used by frameworks like [Slim](http://www.slimframework.com).  
* **[filp/whoops](https://github.com/filp/whoops)**
  * Stunning error handler, it makes errors sting a bit less.  
* **[symfony/http-foundation](https://github.com/symfony/http-foundation)**
  * Simplifies request & reponse handling.
* **[league/container](https://github.com/thephpleague/container)**
  * Dependency injection container, for sharing common resources (like a database connection).
* **[twig/twig](https://github.com/twigphp/Twig)**
  * The rock solid templating engine used by Symfony and many others.
* **[vlucas/phpdotenv](https://github.com/vlucas/phpdotenv)**
  * Please don't push your database credentials to GitHub.  


## Getting started with Vagrant
I've included a Vagrantfile which should make getting setup extremely simple. I assume [composer](https://getcomposer.org) is already installed.

1. Install [Vagrant](https://www.vagrantup.com) and [VirtualBox](https://www.virtualbox.org)
2. `git clone` this repository  
3. `cd` into the cloned repository  
3. `composer install` 
4. `cp .env.example .env`  
5. `vagrant up`

From here, you should be able to access http://localhost:8080/. This website is served using NGINX & PHP 7.


## Getting started with Docker
If you're a Docker user rather than Vagrant, there is a `docker-compose` set up provided.

Follow the instructions above and replace "`vagrant up`" with "`docker-compose up`"

To find the port run `docker-compose port application 80` which will return the public port the application is available under.


## Batteries not included
I've intentionally made this project as simplistic as possible. A lot of things are left up to you to design and implement. On the plus side, you won't have to remove much boilerplate.

Below you will find instructions on how to implement a few things, feel free to contribute more examples :). 

### PDO (database)
Edit ``bootstrap/app.php`` and add the following:
```php
$container->add('PDO')
    ->withArgument(getenv('DB_CONN'))
    ->withArgument(getenv('DB_USER'))
    ->withArgument(getenv('DB_PASS'));
```

You will also need to add some values to your ``.env``
```
# Database access
DB_CONN=mysql:host=127.0.0.1;dbname=frameworkless;charset=utf8
DB_USER=fwl_user
DB_PASS=hopefullysecure
```

Now, from a controller:
```php
    private $pdo;
    
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    public function get()
    {
        $handle = $this->pdo->prepare('SELECT * FROM `todos`');
        $handle->execute();
        return new JsonResponse($handle->fetchAll(PDO::FETCH_ASSOC));
    }
```

### Spot (database, ORM)
```
composer require vlucas/spot2
```

from here, edit ``bootstrap/app.php`` and add the following:
```php
$db = new \Spot\Config();
$db->addConnection('mysql', [
    'dbname'   => getenv('DB_NAME'),
    'user'     => getenv('DB_USER'),
    'password' => getenv('DB_PASS'),
    'host'     => getenv('DB_HOST')
]);

$container->add('\Spot\Locator')
    ->withArgument($db);
```

You will also need to add some values to your ``.env``
```
# Database access
DB_CONN=mysql:host=127.0.0.1;dbname=frameworkless;charset=utf8
DB_USER=fwl_user
DB_PASS=hopefullysecure
```

Now you can create models! I recommend adding them under a src/Models directory for separation. For example ( ``src/Models/Posts.php``):
```php
namespace Frameworkless\Models;

use Spot\Entity;

class Posts extends Entity
{
    protected static $table = 'posts';
    // etc.
}
```

And finally from your controller:
```php
    private $spot;
    
    public function __construct(\Spot\Locator $spot)
    {
        $this->spot = $spot;
    }
    
    public function get()
    {
        $posts = $this->spot->mapper('Frameworkless\Models\Posts')->all();
        return new Response('Here are your posts ' . print_r($posts, true));
    }
```


## Contributing
Submit a pull request :) I'll be friendly

Thanks to **@waxim** for contributing the Spot example  
Thanks to **@jaakkytt** for clearing up part of this readme
