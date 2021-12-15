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
use TheNote\core\Main;

class GodModeCommand extends Command
{
	private $plugin;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
		$config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
		$langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
		$l = $langsettings->get("Lang");
		$lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
		parent::__construct("godmode", $config->get("prefix") . $lang->get("godmodeprefix"), "/godmode", ["god", "gmode"]);
		$this->setPermission("core.command.godmode");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args): bool
	{
		$config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
		$langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
		$l = $langsettings->get("Lang");
		$lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
		if (!$sender instanceof Player) {
			$sender->sendMessage($config->get("error") . $lang->get("commandingame"));
			return false;
		}
		if (!$this->testPermission($sender)) {
			$sender->sendMessage($config->get("error") . $lang->get("nopermission"));
			return false;
		}

		if (!isset(Main::$godmod[$sender->getName()])) {
			Main::$godmod[$sender->getName()] = $sender->getName();
			$sender->sendMessage($config->get("prefix") . $lang->get("godmodeenabled"));
		} else {
			unset(Main::$godmod[$sender->getName()]);
			$sender->sendMessage($config->get("prefix") . $lang->get("godmodedisabled"));
		}
		return true;
	}
}