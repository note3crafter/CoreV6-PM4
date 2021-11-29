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

use pocketmine\crafting\CraftingGrid;
use pocketmine\item\ItemIds;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;
use pocketmine\network\mcpe\protocol\types\inventory\WindowTypes;
use pocketmine\player\Player;
use pocketmine\utils\Config;
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
		$this->sendCraftingTable($sender);
		$sender->getCraftingGrid();
		if(!array_key_exists($windowId = Player::HARDCODED_CRAFTING_GRID_WINDOW_ID, $sender->openHardcodedWindows)) {
			$pk = new ContainerOpenPacket();
			$pk->windowId = $windowId;
			$pk->type = WindowTypes::WORKBENCH;
			$pk->x = $sender->getFloorX();
			$pk->y = $sender->getFloorY() - 2;
			$pk->z = $sender->getFloorZ();
			$sender->sendDataPacket($pk);
			$sender->openHardcodedWindows[$windowId] = true;
		}

		return true;

	}

	public function sendCraftingTable(Player $player)
	{
		$block1 = ItemIds::CRAFTING_TABLE;
		$block1->x = (int)floor($player->getPosition()->x);
		$block1->y = (int)floor($player->getPosition()->y) - 2;
		$block1->z = (int)floor($player->getPosition()->z);
		$block1->level = $player->getWorld();
		$block1->level->sendBlocks([$player], [$block1]);
	}
}
