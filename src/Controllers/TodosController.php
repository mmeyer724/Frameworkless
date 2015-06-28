<?php
namespace Frameworkless\Controllers;

use PDO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TodosController
{
    /**
     * @var PDO
     */
    private $pdo;

    function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function get()
    {
        $handle = $this->pdo->prepare('SELECT * FROM todos');
        $handle->execute();
        return new JsonResponse($handle->fetchAll(PDO::FETCH_ASSOC));
    }

    public function show($args)
    {
        $handle = $this->pdo->prepare('SELECT * FROM todos WHERE id=:id');
        $handle->bindParam(':id', $args['id']);
        $handle->execute();
        if($handle->rowCount() === 0) {
            return new Response('Todo not found', Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse($handle->fetch(PDO::FETCH_ASSOC));
    }

    public function post()
    {
        $request = Request::createFromGlobals();
        $todo_text = $request->get('todo_text');
        if($todo_text === null || empty($todo_text)) {
            return new Response('Must provide todo text', Response::HTTP_BAD_REQUEST);
        }
        $handle = $this->pdo->prepare('INSERT INTO todos (todo_text) VALUES (:text)');
        $handle->bindParam(':text', $todo_text);
        $handle->execute();
        return new Response('Todo created', Response::HTTP_CREATED);
    }
}
