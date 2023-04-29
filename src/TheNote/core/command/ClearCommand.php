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
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class ClearCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
		$api = new BaseAPI();
        parent::__construct("clear", $api->getSetting("prefix") . $api->getLang("clearprefix"), "/clear");
        $this->setPermission("core.command.clear");
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
            if ($sender->hasPermission("core.command.clear.other")) {
                $victim = $api->findPlayer($sender, $args[0]);
                if ($victim == null) {
                    $sender->sendMessage($api->getSetting("error") . $api->getLang("playernotonline"));
                    return false;
                } else {
                    $victim->getInventory()->clearAll();
					$msgplayer = str_replace("{player}" , $sender->getNameTag(), $api->getLang("clearplayer"));
					$victim->sendMessage($api->getSetting("prefix") . $msgplayer);
					$msgvictim = str_replace("{victim}" , $victim, $api->getLang("clearvictim"));
                    $sender->sendMessage($api->getSetting("prefix") . $msgvictim);
                    return false;
                }
            } else {
                $sender->sendMessage($api->getSetting("error") . $api->getLang("clearerror"));
                return false;
            }
        }
        $sender->getInventory()->clearAll();
        $sender->sendMessage($api->getSetting("prefix") . $api->getLang("clearcomfirm"));
        return true;
    }
}
