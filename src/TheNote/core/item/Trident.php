<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\item;

use pocketmine\entity\Entity;
use pocketmine\entity\Location;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\item\ItemUseResult;
use pocketmine\item\Releasable;
use pocketmine\item\Tool;
use pocketmine\player\Player;
use TheNote\core\entity\projectile\TridentEntity;
use TheNote\core\sounds\TridentThrowSound;
use function min;

class Trident extends Tool implements Releasable
{

    public function getMaxDurability(): int
    {
        return 251;
    }

    public function onReleaseUsing(Player|\pocketmine\Player $player): ItemUseResult
    {
        $location = $player->getLocation();

        $diff = $player->getItemUseDuration();
        $p = $diff / 20;
        $baseForce = min((($p ** 2) + $p * 2) / 3, 1) * 3;
        if ($baseForce < 0.9 || $diff < 8) {
            return ItemUseResult::FAIL();
        }

        $entity = new TridentEntity(Location::fromObject(
            $player->getEyePos(),
            $player->getWorld(),
            ($location->yaw > 180 ? 360 : 0) - $location->yaw,
            -$location->pitch
        ), $this, $player);
        $entity->setMotion($player->getDirectionVector()->multiply($baseForce));

        $ev = new ProjectileLaunchEvent($entity);
        $ev->call();
        if ($ev->isCancelled()) {
            $ev->getEntity()->flagForDespawn();
            return ItemUseResult::FAIL();
        }
        $ev->getEntity()->spawnToAll();
        $location->getWorld()->addSound($location, new TridentThrowSound());


        if ($player->hasFiniteResources()) {
            $item = $entity->getItem();
            $item->applyDamage(1);
            $entity->setItem($item);
            $this->pop();
        } else {
            $entity->setPickupMode(TridentEntity::PICKUP_NONE);
        }
        return ItemUseResult::SUCCESS();
    }

    public function getAttackPoints(): int
    {
        return 8;
    }

    public function onAttackEntity(Entity $victim): bool
    {
        return $this->applyDamage(1);
    }
    public function canStartUsingItem(Player $player) : bool{
        return true;
    }
}
