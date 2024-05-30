<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TaskApiController
{
    #[Route('/', methods:['GET'])]
    public function getAllTask():Response
    {
        return new Response('hello World !');
    }
}