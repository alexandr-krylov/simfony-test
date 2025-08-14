<?php

namespace App\Service;

use App\Dto\TaskDto;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;

class TaskService
{
    public function __construct(private EntityManagerInterface $em) {}

    public function getAll(?string $status = null, int $page = 1, int $limit = 10): array
    {
        $qb = $this->em->getRepository(Task::class)->createQueryBuilder('t');

        if ($status) {
            $qb->andWhere('t.status = :status')
               ->setParameter('status', $status);
        }

        $qb->orderBy('t.createdAt', 'DESC');

        return $qb->setFirstResult(($page - 1) * $limit)
                  ->setMaxResults($limit)
                  ->getQuery()
                  ->getResult();
    }

    public function getById(int $id): ?Task
    {
        return $this->em->getRepository(Task::class)->find($id);
    }

    public function create(TaskDto $data): Task
    {
        $task = new Task();
        $task->setTitle($data->title ?? '');
        $task->setDescription($data->description ?? null);
        $task->setStatus($data->status ?? 'pending');
        $task->setCreatedAt(new \DateTimeImmutable());
        $task->setUpdatedAt(new \DateTimeImmutable());

        $this->em->persist($task);
        $this->em->flush();

        return $task;
    }

    public function update(Task $task, TaskDto $data): Task
    {
        if (isset($data->title)) {
            $task->setTitle($data->title);
        }
        if (isset($data->description)) {
            $task->setDescription($data->description);
        }
        if (isset($data->status)) {
            $task->setStatus($data->status);
        }
        $task->setUpdatedAt(new \DateTimeImmutable());

        $this->em->flush();

        return $task;
    }

    public function delete(Task $task): void
    {
        $this->em->remove($task);
        $this->em->flush();
    }

}
