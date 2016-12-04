<?php
namespace Frameworkless\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Twig_Environment;

/**
 * Handles all requests to /greet.
 *
 * @author Michael Meyer <michael@meyer.io>
 */
class GreetController
{
    /** @var Twig_Environment */
    private $twig;

    /**
     * GreetController, constructed by the container
     *
     * @param Twig_Environment $twig
     */
    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Name reversing thing
     *
     * @param array $routeParams
     * @return Response
     */
    public function greet($routeParams)
    {
        $response = new Response(
            $this->twig->render('pages/greet.html.twig', [
                'name' => strrev($routeParams['name'])
            ])
        );
        return $response;
    }
}
