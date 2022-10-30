<?php

namespace TheNote\core\utils;

class SimpleBlockData{

    public $id;
    public $meta;

    public function __construct(int $id, int $meta)
    {
        $this->id = $id;
        $this->meta = $meta;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getMeta(): int
    {
        return $this->meta;
    }
}