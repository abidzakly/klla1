<?php

namespace App\DTOs;

class SubTabel
{
    public ?string $id;
    public ?string $target;
    public ?string $act;
    public string $tableUmumId;

    public function __construct(?string $id, ?string $target, ?string $act, string $tableUmumId)
    {
        $this->id = $id;
        $this->target = $target;
        $this->act = $act;
        $this->tableUmumId = $tableUmumId;
    }
}
