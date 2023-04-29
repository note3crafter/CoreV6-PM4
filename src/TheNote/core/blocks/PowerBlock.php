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

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\math\Vector3;
use pocketmine\world\particle\AngryVillagerParticle;
use pocketmine\world\particle\FlameParticle;
use pocketmine\world\sound\AnvilFallSound;
use pocketmine\world\sound\ClickSound;
use pocketmine\world\sound\EndermanTeleportSound;
use pocketmine\world\sound\PopSound;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class PowerBlock implements Listener
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onMove(PlayerMoveEvent $event)
    {
        $api = new BaseAPI();
        $player = $event->getPlayer();
        $x = $player->getLocation()->getX();
        $y = $player->getLocation()->getY();
        $z = $player->getLocation()->getZ();
        $level = $player->getWorld();
        $block = $level->getBlock($player->getPosition()->getSide(0));
        if ($block->getID() == $api->getConfig("BlockID1")) {
            $direction = $player->getDirectionVector();
            $dx = $direction->getX();
            $dz = $direction->getZ();
            $level->addParticle(new Vector3($x - 0.3, $y, $z), new AngryVillagerParticle());
            $level->addParticle(new Vector3($x, $y, $z - 0.3), new AngryVillagerParticle);
            $level->addParticle(new Vector3($x + 0.3, $y, $z), new AngryVillagerParticle);
            $level->addParticle(new Vector3($x, $y, $z + 0.3), new AngryVillagerParticle);
            if ($api->getConfig("BlockSound1") == "AnvilFallSound") {
                $player->getWorld()->addSound(new Vector3($player->getLocation()->x, $player->getLocation()->y, $player->getLocation()->z), new AnvilFallSound);
            } elseif ($api->getConfig("BlockSound1") == "ClickSound") {
                $player->getWorld()->addSound(new Vector3($player->getLocation()->x, $player->getLocation()->y, $player->getLocation()->z), new ClickSound);
            } elseif ($api->getConfig("BlockSound1") == "EndermanTeleportSound") {
                $player->getWorld()->addSound(new Vector3($player->getLocation()->x, $player->getLocation()->y, $player->getLocation()->z), new EndermanTeleportSound);
            } elseif ($api->getConfig("BlockSound1") == "GhastShootSound") {
                $player->getWorld()->addSound(new Vector3($player->getLocation()->x, $player->getLocation()->y, $player->getLocation()->z), new ClickSound);
            } elseif ($api->getConfig("BlockSound1") == "PopSound") {
                $player->getWorld()->addSound(new Vector3($player->getLocation()->x, $player->getLocation()->y, $player->getLocation()->z), new PopSound);
            }
            //$player->knockBack($dx, $api->getConfig("BlockStaerke1"), $dz, 0);
            $player->knockBack($dx, $dz, $api->getConfig("BlockStaerke1"), 2);

            //$player->setMotion(new Vector3($dx, $api->getConfig("BlockStaerke2"), $dz));
        }
        if ($block->getID() == $api->getConfig("BlockID2")) {
            $direction = $player->getDirectionVector();
            $dx = $direction->getX();
            $dz = $direction->getZ();
            $level->addParticle(new Vector3($x - 0.3, $y, $z), new FlameParticle);
            $level->addParticle(new Vector3($x, $y, $z - 0.3), new FlameParticle);
            $level->addParticle(new Vector3($x + 0.3, $y, $z), new FlameParticle);
            $level->addParticle(new Vector3($x, $y, $z + 0.3), new FlameParticle);
            if ($api->getConfig("BlockSound2") == "AnvilFallSound") {
                $player->getWorld()->addSound(new Vector3($player->getLocation()->x, $player->getLocation()->y, $player->getLocation()->z), new AnvilFallSound);
            } elseif ($api->getConfig("BlockSound2") == "ClickSound") {
                $player->getWorld()->addSound(new Vector3($player->getLocation()->x, $player->getLocation()->y, $player->getLocation()->z), new ClickSound);
            } elseif ($api->getConfig("BlockSound2") == "EndermanTeleportSound") {
                $player->getWorld()->addSound(new Vector3($player->getLocation()->x, $player->getLocation()->y, $player->getLocation()->z), new EndermanTeleportSound);
            } elseif ($api->getConfig("BlockSound2") == "GhastShootSound") {
                $player->getWorld()->addSound(new Vector3($player->getLocation()->x, $player->getLocation()->y, $player->getLocation()->z), new ClickSound);
            } elseif ($api->getConfig("BlockSound2") == "PopSound") {
                $player->getWorld()->addSound(new Vector3($player->getLocation()->x, $player->getLocation()->y, $player->getLocation()->z), new PopSound);
            }
            $player->knockBack($dx, $dz, $api->getConfig("BlockStaerke2"), 2);
            //$player->knockBack($dx, $api->getConfig("BlockStaerke2"), $dz, 0);
            //$player->setMotion(new Vector3($dx, $api->getConfig("BlockStaerke2"), $dz));
        }
    }
}
