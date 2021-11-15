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

use TheNote\core\entity\obejct\BoatEntity;
use TheNote\core\entity\obejct\EnderCrystal;
use TheNote\core\entity\projectile\ThrownTrident;
use pocketmine\entity\Entity;

class EntityManager extends Entity {
	public static function init(): void{
		self::registerEntity(ThrownTrident::class, true, ['Trident', 'minecraft:trident']);
        self::registerEntity(SkullEntity::class, true, ["SkullEntity", "minecraft:skull_entity"]);
        self::registerEntity(FireworksRocket::class, true, ['FireworksRocket', 'minecraft:firework']);
        self::registerEntity(EnderCrystal::class, true, ['EnderCrystal', 'minecraft:ender_crystal']);
        self::registerEntity(BoatEntity::class, true, ['Boat', 'minecraft:boat']);

    }
}
