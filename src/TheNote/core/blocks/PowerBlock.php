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
use pocketmine\utils\Config;
use pocketmine\world\particle\ExplodeParticle;
use pocketmine\world\particle\FlameParticle;
use pocketmine\world\sound\AnvilFallSound;
use pocketmine\world\sound\ClickSound;
use pocketmine\world\sound\EndermanTeleportSound;
use pocketmine\world\sound\PopSound;
use TheNote\core\Main;

class PowerBlock implements Listener
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onMove(PlayerMoveEvent $event)
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "Config" . ".yml", Config::YAML);
        $player = $event->getPlayer();
        $x = $player->getLocation()->getX();
        $y = $player->getLocation()->getY();
        $z = $player->getLocation()->getZ();
        $level = $player->getWorld();
        $block = $level->getBlock($player->getPosition()->getSide(0));
        if ($block->getID() == $config->get("BlockID1")) {
			$direction = $player->getDirectionVector();
			$dx = $direction->getX();
			$dz = $direction->getZ();
			//$level->addParticle(new ExplodeParticle($player));
			$level->addParticle(new Vector3($x - 0.3, $y, $z), new ExplodeParticle);
			$level->addParticle(new Vector3($x, $y, $z - 0.3), new ExplodeParticle);
			$level->addParticle(new Vector3($x + 0.3, $y, $z), new ExplodeParticle);
			$level->addParticle(new Vector3($x, $y, $z + 0.3), new ExplodeParticle);
			if ($config->get("BlockSound1") == "AnvilFallSound") {
				$player->getWorld()->addSound(new Vector3($player->getLocation()->x, $player->getLocation()->y, $player->getLocation()->z), new AnvilFallSound);
			} elseif ($config->get("BlockSound1") == "ClickSound") {
				$player->getWorld()->addSound(new Vector3($player->getLocation()->x, $player->getLocation()->y, $player->getLocation()->z),new ClickSound);
			} elseif ($config->get("BlockSound1") == "EndermanTeleportSound") {
				$player->getWorld()->addSound(new Vector3($player->getLocation()->x, $player->getLocation()->y, $player->getLocation()->z),new EndermanTeleportSound);
			} elseif ($config->get("BlockSound1") == "GhastShootSound") {
				$player->getWorld()->addSound(new Vector3($player->getLocation()->x, $player->getLocation()->y, $player->getLocation()->z),new ClickSound);
			} elseif ($config->get("BlockSound1") == "PopSound") {
				$player->getWorld()->addSound(new Vector3($player->getLocation()->x, $player->getLocation()->y, $player->getLocation()->z),new PopSound);
			}
			$player->knockBack($dx, $dz, $config->get("BlockStaerke2"), 0);
		}
			if ($block->getID() == $config->get("BlockID2")) {
            $direction = $player->getDirectionVector();
            $dx = $direction->getX();
            $dz = $direction->getZ();
            //$level->addParticle( new FlameParticle() ,$player);
            $level->addParticle(new Vector3($x - 0.3, $y, $z), new FlameParticle);
            $level->addParticle(new Vector3($x, $y, $z - 0.3), new FlameParticle);
            $level->addParticle(new Vector3($x + 0.3, $y, $z), new FlameParticle);
            $level->addParticle(new Vector3($x, $y, $z + 0.3), new FlameParticle);
            if ($config->get("BlockSound2") == "AnvilFallSound") {
                $player->getWorld()->addSound(new Vector3($player->getLocation()->x, $player->getLocation()->y, $player->getLocation()->z), new AnvilFallSound);
            } elseif ($config->get("BlockSound2") == "ClickSound") {
                $player->getWorld()->addSound(new Vector3($player->getLocation()->x, $player->getLocation()->y, $player->getLocation()->z),new ClickSound);
            } elseif ($config->get("BlockSound2") == "EndermanTeleportSound") {
                $player->getWorld()->addSound(new Vector3($player->getLocation()->x, $player->getLocation()->y, $player->getLocation()->z),new EndermanTeleportSound);
            } elseif ($config->get("BlockSound2") == "GhastShootSound") {
                $player->getWorld()->addSound(new Vector3($player->getLocation()->x, $player->getLocation()->y, $player->getLocation()->z),new ClickSound);
            } elseif ($config->get("BlockSound2") == "PopSound") {
                $player->getWorld()->addSound(new Vector3($player->getLocation()->x, $player->getLocation()->y, $player->getLocation()->z),new PopSound);
            }
				$player->knockBack($dx, $dz, $config->get("BlockStaerke2"), 0);
        }
    }
}
