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

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\tile\EnderChest;
use pocketmine\block\tile\Tile;
use pocketmine\block\tile\TileFactory;
use pocketmine\block\VanillaBlocks;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\inventory\Inventory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use TheNote\core\invmenu\InvMenu;
use TheNote\core\invmenu\transaction\InvMenuTransaction;
use TheNote\core\Main;

class EnderChestCommand extends Command
{
	private $plugin;
	private $tName;
	private $inv;

	public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("ec", $config->get("prefix") . $lang->get("ecprefix"), "/ec", ["enderchest"]);
        $this->setPermission("core.command.enderchest");
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
	{
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
		$config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . $lang->get("commandingame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . $lang->get("nopermission"));
            return false;
        }
		if ($sender instanceof Player) {
			$this->tName = "";
			$tName = $sender->getName();
			$this->tName = "$tName";
			$sender->sendMessage($config->get("prefix") . $lang->get("ecopen"));
			$this->send($sender);
		}
		return true;
	}
		public function send($sender){
		$menu = InvMenu::create(InvMenu::TYPE_CHEST);
		$inv = $menu->getInventory();
		$menu->setName( $this->tName . "'s Enderchest");
		$target = $this->plugin->getServer()->getPlayerExact($this->tName);
		$content = $target->getEnderInventory()->getContents();
		$this->inv = $menu;
		$inv->setContents($content);
		$menu->setListener(function (InvMenuTransaction $transaction) use ($sender) : \TheNote\core\invmenu\transaction\InvMenuTransactionResult {
			$inv = $this->inv->getInventory();
			$target = $this->plugin->getServer()->getPlayerExact($this->tName);
			if($target->getName() !== $sender->getName()) {
				return $transaction->discard();
			} else {
				$nContents = $inv->getContents();
				$sender->getEnderInventory()->setContents($nContents);
				return $transaction->continue();
			}
		});
		$menu->setInventoryCloseListener(function(Player $sender, Inventory $inventory) : void {
			if($this->tName == $sender->getName()) {
				$nContents = $inventory->getContents();
				$sender->getEnderInventory()->setContents($nContents);
			}
		});
		$menu->send($sender);
	}
}