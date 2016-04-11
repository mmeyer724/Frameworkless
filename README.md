# Frameworkless
An admittedly overly simplistic example of combining popular composer packages into your own pseudo-framework. 


## Why?
For certain applications, a large framework like Symfony or Laravel isn't a great fit. For example, very simple websites lacking authentication, pagination, (many) forms and the like.
 
This repo is intended to be a starting point for others looking to go down the same route that I did. **I do not recommend this route for large projects**, as having a more defined structure and clear rules will help with organization and teamwork. However, being able to understand (and control) every part of your application is extremely nice. I also like to believe frameworkless is faster than other frameworks, but I haven't ran any benchmarks yet. 


## What's included?
I spent some time picking out packages, preferring those used by existing large applications or frameworks. Here is what's included, in no particular order:

**[nikic/fast-route](https://github.com/nikic/FastRoute)** Popular routing library used by frameworks like [Slim](http://www.slimframework.com).  
**[filp/whoops](https://github.com/filp/whoops)** An impressively stunning error handler, it makes errors hurt slightly less.  
**[symfony/http-foundation](https://github.com/symfony/http-foundation)** Makes handling requests and returning responses much easier.  
**[league/container](https://github.com/thephpleague/container)** Dependency injection container, share common objects (like a database connection) in a cleaner way.  
**[twig/twig](https://github.com/twigphp/Twig)** The dependable templating engine used in Symfony.  
**[vlucas/phpdotenv](https://github.com/vlucas/phpdotenv)** Please don't push your credentials to GitHub.  


## Getting started
I've included a Vagrantfile which should make getting setup extremely simple. I assume [composer](https://getcomposer.org) is installed.

Step 1. Install [Vagrant](https://www.vagrantup.com)  
Step 2. Clone this repository  
Step 3. cd into the repository  
Step 3. ```composer install```  
Step 4. ```cp .env.example .env```  
Step 5. ```vagrant up```

From here, you should be able to browse to http://localhost:8080/. The website is served with NGINX and PHP 7.


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
