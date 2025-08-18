<?php
namespace App\Tests\Service;

use App\Dto\TaskDto;
use App\Entity\Task;
use App\Service\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityRepository;

class TaskServiceTest extends TestCase
{
    private $em;
    private $repository;
    private $service;

    protected function setUp(): void
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->repository = $this->createMock(EntityRepository::class);
        $this->em->method('getRepository')
            ->willReturn($this->repository);

        $this->service = new TaskService($this->em);
    }

    public function testGetByIdReturnsTask()
    {
        $task = new Task();
        $task->setTitle('Test Task');

        $this->repository->method('find')->with(1)->willReturn($task);

        $result = $this->service->getById(1);

        $this->assertSame($task, $result);
    }

    public function testGetByIdReturnsNullIfNotFound()
    {
        $this->repository->method('find')->with(999)->willReturn(null);

        $result = $this->service->getById(999);

        $this->assertNull($result);
    }

    public function testCreatePersistsAndFlushes()
    {
        $dto = new TaskDto(title: 'New Task',description: 'Description');

        $this->em->expects($this->once())
        ->method('persist')
        ->with($this->isInstanceOf(Task::class));
        $this->em->expects($this->once())->method('flush');

        $task = $this->service->create($dto);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('New Task', $task->getTitle());
        $this->assertEquals('Description', $task->getDescription());
        $this->assertEquals('pending', $task->getStatus());
    }

    public function testUpdateChangesFieldsAndFlushes()
    {
        $task = new Task();
        $task->setTitle('Old Title');
        $task->setDescription('Old Description');
        $task->setStatus('pending');

        $dto = new TaskDto(
            title: 'Updated Title',
            description: 'Updated Description',
            status: 'done'
        );

        $this->em->expects($this->once())->method('flush');

        $updated = $this->service->update($task, $dto);

        $this->assertEquals('Updated Title', $updated->getTitle());
        $this->assertEquals('Updated Description', $updated->getDescription());
        $this->assertEquals('done', $updated->getStatus());
    }

    public function testDeleteRemovesAndFlushes()
    {
        $task = new Task();
        $task->setTitle('To be deleted');

        $this->em->expects($this->once())->method('remove')->with($task);
        $this->em->expects($this->once())->method('flush');

        $this->service->delete($task);

        $this->assertTrue(true);

    }
}
