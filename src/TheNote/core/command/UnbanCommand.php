<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class UnbanCommand extends Command {

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("unban", $api->getSetting("prefix") . "Entbanne einen Spieler", "/unban", ["pardon"]);
        $this->setPermission("core.command.unban");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getSetting("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        $banlist = new Config($this->plugin->getDataFolder() . "banned-players.json", Config::JSON);

        if(empty($args[0])) {
            $sender->sendMessage($this->getUsage());
            return true;
        }
        $name = $args[0];
        if(!$banlist->get(strtolower($name))) {
            $sender->sendMessage($api->getSetting("error") . "§cDieser Spieler ist nicht gebannt.");
            return true;
        }
        $banlist->remove(strtolower($name));
        $banlist->save();
        $banlist->reload();
        $sender->sendMessage($api->getSetting("ban") . "§aDer Spieler §2$args[0] §awurde entbannt.");
        return false;
    }
}