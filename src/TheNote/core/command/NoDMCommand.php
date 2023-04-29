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

use pocketmine\player\Player;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class NoDMCommand extends Command
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("notell", $api->getSetting("prefix") . $api->getLang("nodmprefix"), "/notell <on|off>", ["dm", "pn", "nodm"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("commandingame"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($api->getSetting("info") . $api->getLang("nodmusage"));
            return true;
        }
        $cfg = new Config($this->plugin->getDataFolder() . Main::$userfile . $sender->getName() . ".json", Config::JSON);
        if (isset($args[0])) {
            if ($args[0] == "on") {
                $cfg->set("nodm", false);
                $cfg->save();
                $sender->sendMessage($api->getSetting("info") . $api->getLang("nodmunlock"));
            }
            if ($args[0] == "off") {
                $cfg->set("nodm", true);
                $cfg->save();
                $sender->sendMessage($api->getSetting("info") . $api->getLang("nodmlock"));
            }
        }
        return true;
    }
}