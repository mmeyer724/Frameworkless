<?php

return [
    // Hello
    ['GET', '/', ['Frameworkless\Controllers\HelloController', 'get']],

    // Todos
    ['GET', '/todos', ['Frameworkless\Controllers\TodosController', 'get']],
    ['POST', '/todos', ['Frameworkless\Controllers\TodosController', 'post']],
    ['GET', '/todos/{id:\d+}', ['Frameworkless\Controllers\TodosController', 'show']],
];
