<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\blocks;

use pocketmine\block\Slab;
use pocketmine\block\utils\HorizontalFacingTrait;
use pocketmine\block\utils\SlabType;

class PolishedBlackstoneSlab extends Slab
{
    use HorizontalFacingTrait;

    public function writeStateToMeta() : int{
        if(!$this->slabType->equals(SlabType::DOUBLE())){
            return ($this->slabType->equals(SlabType::TOP()) ? 1 : 0);
        }
        return 0;
    }

    public function readStateFromData(int $id, int $stateMeta) : void{
        if($id === $this->idInfoFlattened->getSecondId()){
            $this->slabType = SlabType::DOUBLE();
        }else{
            $this->slabType = ($stateMeta === 1 ? SlabType::TOP() : SlabType::BOTTOM());
        }
    }
    public function getStateBitmask(): int{
        return 1;
    }
}