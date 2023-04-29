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
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class DelHomeCommand extends Command
{
	private Main $plugin;

	public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
		$api = new BaseAPI();
        parent::__construct("delhome", $api->getSetting("prefix") . $api->getLang("delhomeprefix"), "/delhome <home>");
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
        if (empty($args[0])) {
            $sender->sendMessage($api->getSetting("prefix") . $api->getLang("delhomeusage"));
            return true;
        }
        if ($api->getHomeExist($sender->getName(), $args[0])) {
            $api->setHomeRemove($sender, $args[0]);
            $api->rmUserPoint($sender, 1);
            $sender->sendMessage($api->getSetting("prefix") . $api->getLang("delhomeconfirm"));
        } else {
            $message = str_replace("{home}" , $args[0], $api->getLang("delhomenotfound"));
            $sender->sendMessage($api->getSetting("error") . $message);
        }
        return true;
    }
}
