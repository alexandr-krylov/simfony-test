<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class TaskDto
{
    public function __construct(
        #[Assert\NotBlank(message: "Название задачи обязательно.")]
        #[Assert\Length(
            max: 255,
            maxMessage: "Название не может быть длиннее {{ limit }} символов."
        )]
        #[Assert\Length(
            min: 3,
            minMessage: "Название должно содержать минимум {{ limit }} символа."
        )]
        public string $title,
        #[Assert\NotBlank(message: "Описание задачи обязательно.")]
        #[Assert\Length(
            max: 1000,
            maxMessage: "Описание не может быть длиннее {{ limit }} символов."
        )]
        public ?string $description,
        #[Assert\Length(
            max: 50,
            maxMessage: "Название не может быть длиннее {{ limit }} символов."
        )]
        public ?string $status = 'pending',
    ) {}
}
