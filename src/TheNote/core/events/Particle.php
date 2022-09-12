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

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJumpEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\types\ParticleIds;
use pocketmine\utils\Config;
use pocketmine\world\particle\AngryVillagerParticle;
use pocketmine\world\particle\ExplodeParticle;
use pocketmine\world\particle\FlameParticle;
use pocketmine\world\particle\HeartParticle;
use pocketmine\world\particle\LavaParticle;
use pocketmine\world\particle\PortalParticle;
use pocketmine\world\particle\RedstoneParticle;
use pocketmine\world\particle\SmokeParticle;
use pocketmine\world\particle\SplashParticle;
use pocketmine\world\particle\SporeParticle;
use TheNote\core\Main;

class Particle implements Listener
{
	private Main $plugin;

	public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
    public function onMove(PlayerMoveEvent $event)
    {

        $level = $event->getPlayer()->getWorld();
        $player = $event->getPlayer();
        //$player->setFood(20);
        $x = $player->getLocation()->getX();
        $y = $player->getLocation()->getY();
        $z = $player->getLocation()->getZ();
        $pos = new Vector3($x, $y , $z);
        $pf =  new Config($this->plugin->getDataFolder() . Main::$userfile . $player->getName() . ".json", Config::JSON);
        if ($pf->get("explode") === true) {

			$level->addParticle(new Vector3($x, $y, $z),new ExplodeParticle);

        } else if ($pf->get("angry") === true) {
			$level->addParticle(new Vector3($x, $y, $z),new AngryVillagerParticle);

        } else if ($pf->get("redstone") === true) {

			$level->addParticle(new Vector3($x, $y, $z),new RedstoneParticle);

        } else if ($pf->get("smoke") === true) {

			$level->addParticle(new Vector3($x, $y, $z),new SmokeParticle);

        } else if ($pf->get("lava") === true) {

			$level->addParticle(new Vector3($x, $y, $z),new LavaParticle);

        } else if ($pf->get("heart") === true) {

			$level->addParticle(new Vector3($x, $y, $z),new HeartParticle);

        } else if ($pf->get("flame") === true) {

			$level->addParticle(new Vector3($x, $y, $z),new FlameParticle);

        } else if ($pf->get("portal") === true) {

			$level->addParticle(new Vector3($x, $y, $z),new PortalParticle);

        } else if ($pf->get("spore") === true) {

			$level->addParticle(new Vector3($x, $y, $z),new SporeParticle);

        } else if ($pf->get("splash") === true) {

			$level->addParticle(new Vector3($x, $y, $z),new SplashParticle);

        }
    }
    public function onJump(PlayerJumpEvent $event) {

        $player = $event->getPlayer();
        $pf =  new Config($this->plugin->getDataFolder() . Main::$userfile . $player->getName() . ".json");
        if ($pf->get("DJ") === true) {

            $yaw = $player->getLocation()->getYaw();
            if ($yaw < 45 && $yaw > 0 || $yaw < 360 && $yaw > 315) {

                $player->setMotion(new Vector3(0, 1, 1));

            } else if ($yaw < 135 && $yaw > 45) {

                $player->setMotion(new Vector3(-1, 1, 0));

            } else if ($yaw < 225 && $yaw > 135) {

                $player->setMotion(new Vector3(0, 1, -1));

            } elseif($yaw < 315 && $yaw > 225){

                $player->setMotion(new Vector3(1, 1, 0));

            }

        }

    }
}