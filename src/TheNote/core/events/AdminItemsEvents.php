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
use pocketmine\utils\Config;
use pocketmine\world\Explosion;
use pocketmine\world\Position;
use TheNote\core\Main;

class AdminItemsEvents implements Listener
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onShoot(EntityShootBowEvent $event)
    {
        $bogen = $event->getBow();
        if ($bogen->getNamedTag()->getCompoundTag("custom_data")) {
            $value = $bogen->getNamedTag()->getString("custom_data");
            if ($value == "super_bow") {
                $projectile = $event->getProjectile();
                $projectile->setNameTag("string" . ["custom_data", "super_arrow"]);
                $projectile->setNameTag();
            }
            if ($value == "explode_bow") {
                $projectile = $event->getProjectile();
                $projectile->getNameTag()->setString("custom_data", "explode_arrow");
            }
        }
    }

    public function onProjektileHit(ProjectileHitBlockEvent $event)
    {
        $configs = new Config($this->plugin->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        $entity = $event->getEntity();
        $block = $event->getBlockHit();
        $radius = 5;
        if ($entity->getString("custom_data")) {
            $value = $entity->getNameTag() === "custom_data";
            if ($value == "explode_egg") {
                if (!$event->getEntity() instanceof Egg) {
                    return;
                }
                $explosion = new Explosion($event->getEntity()->getPosition(), $radius);
                $event->getEntity()->kill();
                $explosion->explodeA();
                $explosion->explodeB();
            }
            if ($value == "super_arrow") {
                $entity->flagForDespawn();
                $block->getPosition()->getWorld()->setBlock($block->getPosition(), BlockFactory::getInstance()->get(0, 0, null));
                $block->getPosition()->getWorld()->dropItem($block->getPosition(), ItemFactory::getInstance()->get($block->getId(), $block->getDamage(), 1));
            }
            if ($value == "explode_arrow") {
                if (!$event->getEntity() instanceof Arrow) {
                    return;
                }
                $explosion = new Explosion($event->getEntity()->getPosition(), $radius);
                $event->getEntity()->kill();
                $explosion->explodeA();
                $explosion->explodeB();
            }
        }
        if ($configs->get("ExplodeEgg") == true) {
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