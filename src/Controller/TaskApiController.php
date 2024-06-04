<?php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class TaskApiController extends AbstractController
{
    #[Route('/task', name:'show_task', methods:['GET'])]
    public function showTask(EntityManagerInterface $em): Response
    {
        $task = $em->getRepository(Task::class)->findAll();
        return $this->json($task);
    }

    #[Route('/task', name: 'create_task', methods:['POST'])]
    public function createTask(EntityManagerInterface $em, Request $request): Response
    {
        $param = json_decode($request->getContent(), true);

        $task = new Task();
        $task->setName($param['name'])
            ->setStatus($param['status']);

        $em->persist($task);
        $em->flush();

        return new Response(sprintf(
            'New task save ! id:#%d / name: %s',
            $task->getId(),
            $task->getName()
        ));
    }

    #[Route('/task/{id}', name:'update_task', methods:['PUT'])]
    public function editTask(EntityManagerInterface $em, Request $request, int $id)
    {
        $param = json_decode($request->getContent(), true);

        $task = $em->getRepository(Task::class)->find($id);

        if(!$task) {
            throw $this->createNotFoundException(
                'No task found for id: '.$id
            );
        }

        $task->setName($param['name']);
        $task->setStatus($param['status']);

        $em->persist($task);
        $em->flush();

        return new Response(sprintf(
            'Task update ! id:#%d / name: %s',
            $task->getId(),
            $task->getName()
        ));
    }

    #[Route('/task/{id}', name:'delete_task', methods:['DELETE'])]
    public function deleteTask(EntityManagerInterface $em, int $id)
    {
        $task = $em->getRepository(Task::class)->find($id);

        if(!$task) {
            throw $this->createNotFoundException(
                'No task found for id: '.$id
            );
        }

        $em->remove($task);
        $em->flush();

        return new Response('Task delete !');

    }
    
}
