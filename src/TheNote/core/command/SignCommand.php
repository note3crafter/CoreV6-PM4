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
use pocketmine\utils\TextFormat as TF;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class SignCommand extends Command {

    private $plugin;

	public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
		parent::__construct("sign", $api->getSetting("prefix") . $api->getLang("signprefix"), "/sign <text>");
		$this->setPermission("core.command.sign");
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
		if(empty($args)) {
			$sender->sendMessage($api->getSetting("info") . $api->getLang("signusage"));
			return false;
		}
		$item = $sender->getInventory()->getItemInHand();
        $date = date("d.m.Y");
        $time = date("H:i:s");
        $name = $sender->getName();
        $fullargs = implode(" ", $args);
        $item->clearCustomName();
        $item->setLore([$this->convert("{date} um {time}", $date, $time, $name)."\n".$this->convert("Signiert von {name}", $date, $time, $name)]);
		$item->setCustomName(str_replace("&", TF::ESCAPE, $fullargs));
        $sender->getInventory()->setItemInHand($item);
        $sender->sendMessage($api->getSetting("prefix") . $api->getLang("signsucces"));
        return true;
    }

    public function convert(string $string, $date, $time, $name): string{
        $string = str_replace("{date}", $date, $string);
        $string = str_replace("{time}", $time, $string);
        $string = str_replace("{name}", $name, $string);
        return $string;
	}
}