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

class KreativCommand extends Command
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("gmc", $api->getSetting("prefix") . $api->getLang("creativeprefix"), "/gmc", ["creative", "gm1"]);
        $this->setPermission("core.command.creativ");
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
            if ($sender->hasPermission("core.command.creativ.other")) {
                $target = $api->findPlayer($sender, $args[0]);
                if ($target == null) {
                    $sender->sendMessage($api->getSetting("error") . $api->getLang("playernotonline"));
                    return false;
                } else {
                    $target->setGamemode(GameMode::CREATIVE());
                    $cfgmsg = str_replace("{victim}", $target->getName(), $api->getLang("creativetarget2"));
                    $target->sendMessage($api->getSetting("prefix") . $api->getLang("creativetarget1"));
                    $sender->sendMessage($api->getSetting("prefix") . $cfgmsg);
                    return false;
                }
            } else {
                $sender->sendMessage($api->getSetting("error") . $api->getLang("creativenopermtarget"));
                return false;
            }
        }
        $sender->setGamemode(GameMode::CREATIVE()) ;
        $sender->sendMessage($api->getSetting("prefix") . $api->getLang("creativesender"));
        return true;
    }
}