<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\utils\Config;
use TheNote\core\Main;

class  CollisionsListener implements Listener
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onMove(PlayerMoveEvent $ev)
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        if ($config->get("collision") == true) {
            $player = $ev->getPlayer();
            foreach ($player->getViewers() as $viewer) {
                if ($player->getPosition()->distance($viewer->getPosition()) > 0.5) continue;
                $speed = abs($player->getMotion()->x) + abs($player->getMotion()->z);
                if ($speed > 2) {
					$viewer->knockBack($player->getPosition()->x + $viewer->getPosition()->x, $player->getPosition()->z + $viewer->getPosition()->z, 0.3,0);
					$player->knockBack($player->getPosition()->x + $viewer->getPosition()->x, $player->getPosition()->z + $viewer->getPosition()->z, 0.1,0);
					break;
                }
                if ($speed < 2) {
					$viewer->knockBack($player->getPosition()->x + $viewer->getPosition()->x, $player->getPosition()->z + $viewer->getPosition()->z, 0.2,0);
					$player->knockBack($player->getPosition()->x + $viewer->getPosition()->x, $player->getPosition()->z + $viewer->getPosition()->z, 0.1,0);
                    break;
                }
            }
        }
    }
}