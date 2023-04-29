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

class GodModeCommand extends Command
{
	private $plugin;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
		$api = new BaseAPI();
		parent::__construct("godmode", $api->getSetting("prefix") . $api->getLang("godmodeprefix"), "/godmode", ["god", "gmode"]);
		$this->setPermission("core.command.godmode");
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

		if (!isset(Main::$godmod[$sender->getName()])) {
			Main::$godmod[$sender->getName()] = $sender->getName();
			$sender->sendMessage($api->getSetting("prefix") . $api->getLang("godmodeenabled"));
		} else {
			unset(Main::$godmod[$sender->getName()]);
			$sender->sendMessage($api->getSetting("prefix") . $api->getLang("godmodedisabled"));
		}
		return true;
	}
}