# Frameworkless
Blog post: <link here>

Frameworkless is a simple example of how you can use just a few composer packages to create an awesomely-simple diy micro-framework. Check out the code for usage examples, everything is extremely simply and easy to understand.

## Using Twig
If you're not just serving up JSON and raw responses, you might want to check out Twig. To use it, run: "composer require twig/twig". Then, inside of app.php, add:
````
$container->add('Twig_Environment')
    ->withArgument(new Twig_Loader_Filesystem(__DIR__ . '/../views/'))
````
Create a "views" directory inside of your project, and start adding *.html.twig files. To return a response with twig, inside of your controller, first add:
````
    private $twig;

    function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }
````
This will tell the container to inject a Twig_Environment instance to your controller. From there, inside a method add:
````
return new Response($this->twig->render('page.html.twig', $data));
````

## Contributing
File an issue, or even better, submit a pull request. I'll review it asap. You can also email me at: mcm1792 at rit.edu
