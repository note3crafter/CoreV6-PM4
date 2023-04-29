<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\task;

use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class PingTask extends Task
{

	private Main $plugin;

	public function __construct(Main $main)
    {
        $this->plugin = $main;
    }
    public function onRun() : void
    {
        $api = new BaseAPI();
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
            if ($player->getNetworkSession()->getPing() >= $api->getConfig("PingLimit")) {
                $player->kick($api->getSetting("prefix") . "Du wurdest wegen einem zu Hohen Ping gekickt! Das Limit beträgt" . $api->getConfig("PingLimit" ));
            }
        }
    }
}