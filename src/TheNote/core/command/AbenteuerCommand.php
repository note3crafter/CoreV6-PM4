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
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class AbenteuerCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("gma", $api->getSetting("prefix") . $api->getLang("adventureprefix"), "/gma", ["abenteuer", "gm2"]);
        $this->setPermission("core.command.adventure");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): int
    {
        $api = new BaseAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("commandingame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("nopermission"));
            return false;
        }
        if (isset($args[0])) {
            if ($sender->hasPermission("core.command.adventure.other")) {
                $target = $api->findPlayer($sender, $args[0]);
                if ($target == null) {
                    $sender->sendMessage($api->getSetting("error") . $api->getLang("playernotonline"));
                    return false;
                } else {
                    $target->setGamemode(GameMode::ADVENTURE());
                    $cfgmsg = str_replace("{victim}", $target->getName(), $api->getLang("adventuretarget2"));
                    $target->sendMessage($api->getSetting("prefix") . $api->getLang("adventuretarget1"));
                    $sender->sendMessage($api->getSetting("prefix") . $cfgmsg);
                    return false;
                }
            } else {
                $sender->sendMessage($api->getSetting("error") . $api->getLang("adventurenopermtarget"));
                return false;
            }
        }
        $sender->setGamemode(GameMode::ADVENTURE()) ;
        $sender->sendMessage($api->getSetting("prefix") . $api->getLang("adventuresender"));
        return true;
    }
}