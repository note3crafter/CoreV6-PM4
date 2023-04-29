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

use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\Server;
use TheNote\core\BaseAPI;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;

class ZuschauerCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("gmspc", $api->getSetting("prefix") . $api->getLang("spectatorprefix"), "/gmspc", ["spectator", "zuschauer", "gm3"]);
        $this->setPermission("core.command.spectator");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
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
            if ($sender->hasPermission("core.command.spectator.other")) {
                $target = $api->findPlayer($sender, $args[0]);
                if ($target == null) {
                    $sender->sendMessage($api->getSetting("error") . $api->getLang("playernotonline"));
                    return false;
                } else {
                    $target->setGamemode(GameMode::SPECTATOR());
                    $cfgmsg = str_replace("{victim}", $target->getName(), $api->getLang("spectatortarget2"));
                    $target->sendMessage($api->getSetting("prefix") . $api->getLang("spectatortarget1"));
                    $sender->sendMessage($api->getSetting("prefix") . $cfgmsg);
                    return false;
                }
            } else {
                $sender->sendMessage($api->getSetting("error") . $api->getLang("spectatornopermtarget"));
                return false;
            }
        }
        $sender->setGamemode(GameMode::SPECTATOR()) ;
        $sender->sendMessage($api->getSetting("prefix") . $api->getLang("spectatorsender"));
        return true;
    }
}