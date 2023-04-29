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
use TheNote\core\BaseAPI;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;

class DayCommand extends Command
{
	private Main $plugin;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
		$api = new BaseAPI();
		parent::__construct("day", $api->getSetting("prefix") . $api->getLang("dayprefix"), "/day", ["tag"]);
		$this->setPermission("core.command.day");
	}
	public function execute(CommandSender $sender, string $commandLabel, array $args): bool
	{
		$api = new BaseAPI();
		if (!$sender instanceof Player) {
			$sender->sendMessage($api->getSetting("error") . $api->getLang("commandingame"));
			return false;
		}
		if (!$this->testPermission($sender)) {
			$sender->sendMessage($api->getSetting("error") . $api->getLang("nopermissions"));
			return false;
		}
		$sender->getWorld()->setTime(0);
		$sender->sendMessage($api->getSetting("prefix") . $api->getLang("dayconfirm"));
		$br = str_replace("{player}" , $sender->getNameTag(), $api->getLang("daybroadcast"));
		$this->plugin->getServer()->broadcastMessage($br);
		return true;
	}
}