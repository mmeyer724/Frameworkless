<?php

return [
    ['GET', '/', ['Frameworkless\Controllers\IndexController', 'index']],
    ['GET', '/exception', ['Frameworkless\Controllers\IndexController', 'exception']],
    ['GET', '/greet/{name}', ['Frameworkless\Controllers\GreetController', 'greet']],
];
