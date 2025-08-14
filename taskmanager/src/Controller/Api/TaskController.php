<?php

namespace App\Controller\Api;

use App\Dto\TaskDto;
use App\Service\TaskService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/tasks')]
final class TaskController extends AbstractController
{
    public function __construct(
        private TaskService $taskService,
        private ValidatorInterface $validator,
        private LoggerInterface $logger
    ) {}

    #[Route('', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $status = $request->query->get('status');
        $page = (int) $request->query->get('page', 1);
        $limit = (int) $request->query->get('limit', 10);
        $tasks = $this->taskService->getAll($status, $page, $limit);

        return $this->json($tasks);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $task = $this->taskService->getById($id);

        if (!$task) {
            return $this->json(['error' => 'Not Found'], 404);
        }

        return $this->json($task);
    }

    #[Route('', methods: ['POST'])]
    public function store(
        #[MapRequestPayload(validationGroups: ['Default'])] TaskDto $dto,
        TaskService $service,
        ): JsonResponse
    {
        $task = $service->create($dto);
        return $this->json($task);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, #[MapRequestPayload(validationGroups: ['Default'])] TaskDto $dto,
        TaskService $service): JsonResponse
    {
        $task = $service->getById($id);
        if (!$task) {
            return $this->json(['error' => 'Not Found'], 404);
        }

        $task = $service->update($task, $dto);

        return $this->json($task);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $task = $this->taskService->getById($id);
        if (!$task) {
            return $this->json(['error' => 'Not Found'], 404);
        }

        $this->taskService->delete($task);

        return $this->json(null, 204);
    }
}
