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

use pocketmine\block\VanillaBlocks;
use pocketmine\network\mcpe\protocol\types\inventory\WindowTypes;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use TheNote\core\invmenu\InvMenu;
use TheNote\core\invmenu\type\InvMenuType;
use TheNote\core\invmenu\type\util\InvMenuTypeBuilders;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class CraftCommand extends Command
{

	private $plugin;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
		$config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
		parent::__construct("craft", $config->get("prefix") . "Benutze die CraftingTable Unterwegs", "/craft", ["crafting"]);
		$this->setPermission("core.command.craft");

	}

	public function execute(CommandSender $sender, string $commandLabel, array $args): bool
	{
		$config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
		if (!$sender instanceof Player) {
			$sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
			return false;
		}
		if (!$this->testPermission($sender)) {
			$sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
			return false;
		}
		$sender->sendMessage($config->get("error") . "Error 404 Command not found!");

		return true;

	}
}
