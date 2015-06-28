<?php
namespace Frameworkless\Controllers;

use Symfony\Component\HttpFoundation\Response;

class HelloController
{
    public function get()
    {
        return new Response('Hello there!');
    }
}
