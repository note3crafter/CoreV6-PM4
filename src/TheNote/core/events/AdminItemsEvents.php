<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\events;

use pocketmine\block\BlockFactory;
use pocketmine\entity\projectile\Arrow;
use pocketmine\entity\projectile\Egg;
use pocketmine\event\entity\EntityShootBowEvent;
use pocketmine\event\entity\ProjectileHitBlockEvent;
use pocketmine\event\Listener;
use pocketmine\item\ItemFactory;
use pocketmine\item\StringItem;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\Config;
use pocketmine\world\Explosion;
use pocketmine\world\Position;
use TheNote\core\Main;

class AdminItemsEvents implements Listener
{
    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onShoot(EntityShootBowEvent $event)
    {
        $bogen = $event->getBow();
        if ($bogen->getNamedTag()->getString("custom_data")) {
            $value = $bogen->getNamedTag()->getString("custom_data");
            $projectile = $event->getProjectile();
            if ($value == "super_bow") {
                $array = ["custom_data", "super_arrow"];
            } elseif ($value == "explode_bow") {
                $array = ["custom_data", "explode_arrow"];
            } else return;
            $projectile->setNameTag(implode(",", $array));
        }
    }

    public function onProjektileHit(ProjectileHitBlockEvent $event)
    {
        $configs = new Config($this->plugin->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        $entity = $event->getEntity();
        $block = $event->getBlockHit();
        $radius = 5;
        $nameTag = $entity->getNameTag();
        $array = explode(",", $nameTag);
        if (count($array) === 2) {
            if ($array[0] === "custom_data") {
                $value = $array[1] ?? "";
                if ($value == "explode_egg") {
                    if (!$event->getEntity() instanceof Egg)
                        return;
                    $explosion = new Explosion($event->getEntity()->getPosition(), $radius);
                    $event->getEntity()->kill();
                    $explosion->explodeA();
                    $explosion->explodeB();
                }
                if ($value == "super_arrow") {
                    $entity->flagForDespawn();
                    $block->getPosition()->getWorld()->setBlock($block->getPosition(), BlockFactory::getInstance()->get(0, 0));
                    $block->getPosition()->getWorld()->dropItem($block->getPosition(), ItemFactory::getInstance()->get($block->asItem()->getId(), $block->asItem()->getMeta()));
                }
                if ($value == "explode_arrow") {
                    if (!$event->getEntity() instanceof Arrow)
                        return;
                    $explosion = new Explosion($event->getEntity()->getPosition(), $radius);
                    $event->getEntity()->kill();
                    $explosion->explodeA();
                    $explosion->explodeB();
                }
            }
        }
        if ($configs->get("ExplodeEgg", true)) {
            if ($entity instanceof Egg) {
                $theX = $entity->getLocation()->getX();
                $theY = $entity->getLocation()->getY();
                $theZ = $entity->getLocation()->getZ();
                $level = $entity->getWorld();
                $thePosition = new Position($theX, $theY, $theZ, $level);
                $theExplosion = new Explosion($thePosition, 5, NULL);
                $theExplosion->explodeB();
            }
        }
    }
}