<?php

namespace App\DTOs;

class SubTabel
{
    public ?string $id;
    public ?string $target;
    public ?string $act;
    public string $tableUmumId;
    public ?string $row_id;

    public function __construct(?string $id, ?string $target, ?string $act, string $tableUmumId, ?string $row_id)
    {
        $this->id = $id;
        $this->target = $target;
        $this->act = $act;
        $this->tableUmumId = $tableUmumId;
        $this->row_id = $row_id;
    }
}
