<?php

namespace TheNote\core\blocks;

use pocketmine\block\Transparent;

class CrimsonSign extends Transparent
{
    public function canBePlaced() : bool{
        return true;
    }
}