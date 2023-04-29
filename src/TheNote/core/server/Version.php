<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server;

use TheNote\core\BaseAPI;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;

class Version extends Command {

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("version", $api->getSetting("prefix") . "§6Zeigt die Version des Servers an", "/version" ,["ver"]);
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $api = new BaseAPI();
        if (!$this->testPermission($sender)){
            return false;
        }
        $v = Main::$version;
        $p = Main::$protokoll;
        $mcpe = Main::$mcpeversion;
        $date = Main::$dateversion;
		$pmmpv = $this->plugin->getServer()->getPocketMineVersion();
        $sender->sendMessage($api->getSetting("info"). "Dieser Server läuft auf PocketMine-MP $pmmpv mit Core Version $v für Minecraft: Bedrock Edition v$mcpe (Protokollversion $p) Stand : $date ");
        return false;
    }
}