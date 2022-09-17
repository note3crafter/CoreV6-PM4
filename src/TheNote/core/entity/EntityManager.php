<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\entity;

use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\data\SavedDataLoadingException;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\world\World;
use TheNote\core\entity\projectile\TridentEntity;

class EntityManager {
    public static function init()
    {
        EntityFactory::getInstance()->register(TridentEntity::class, function (World $world, CompoundTag $nbt): TridentEntity {
            $itemTag = $nbt->getCompoundTag("Trident");
            if ($itemTag === null) {
                throw new SavedDataLoadingException("Expected \"Trident\" NBT tag not found");
            }

            $item = Item::nbtDeserialize($itemTag);
            if($item->isNull()){
                throw new SavedDataLoadingException("Trident Item is invalid");
            }
            return new TridentEntity(EntityDataHelper::parseLocation($nbt, $world), $item, null, $nbt);
        }, ['Trident', 'ThrownTrident', 'minecraft:trident' , 'minecraft:trown_trident'], EntityLegacyIds::TRIDENT);
    }
}