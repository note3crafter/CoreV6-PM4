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
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class RegelEvent implements Listener {

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
    public function onMove(PlayerMoveEvent $event) {
        $name = $event->getPlayer();
        $player = $event->getPlayer()->getName();
        $api = new BaseAPI();
        if ($api->getUser($player,"rulesaccpet") === false){
            $event->cancel();
            $name->sendTip($api->getSetting("error")  . "§cDu musst die Regeln Bestätigen um auf dem Server Spielen zu können!!\n §r§d/regeln");
        }
    }
    public function onChat(PlayerChatEvent $event) {
        $name = $event->getPlayer();
        $player = $event->getPlayer()->getName();
        $api = new BaseAPI();
        if ($api->getUser($player,"rulesaccpet") === false){
            $event->cancel();
            $name->sendTip($api->getSetting("error") . "§cDu musst die Regeln Bestätigen um auf dem Server Spielen zu können!!\n §r§d/regeln");
        }
    }
    public function onInteract(PlayerInteractEvent $event) {
        $name = $event->getPlayer();
        $player = $event->getPlayer()->getName();
        $api = new BaseAPI();
        if ($api->getUser($player,"rulesaccpet") === false){
            $event->cancel();
            $name->sendTip($api->getSetting("error")  . "§cDu musst die Regeln Bestätigen um auf dem Server Spielen zu können!!\n §r§d/regeln");
        }
    }
}