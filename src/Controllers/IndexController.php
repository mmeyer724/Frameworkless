<?php
namespace Frameworkless\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Twig_Environment;

class IndexController
{
    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * IndexController, constructed by container
     *
     * @param Twig_Environment $twig
     */
    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Return index page (/)
     *
     * @param array $args
     * @return Response
     */
    public function get($args)
    {
        if(isset($args['name'])) {
            return new Response($this->twig->render('pages/index.html.twig', ['name' => strrev($args['name'])]));
        }
        return new Response($this->twig->render('pages/index.html.twig'));
    }
}
