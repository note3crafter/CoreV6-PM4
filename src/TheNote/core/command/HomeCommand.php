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
use pocketmine\world\Position;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class HomeCommand extends Command
{
	private Main $plugin;

	public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("home", $api->getSetting("prefix") . $api->getLang("homeprefix"), "/home <home>");
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("commandingame"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($api->getSetting("info") . $api->getLang("homeussage"));
        }
        if (isset($args[0])) {
            $name = $args[0];
            $home = new Config($this->plugin->getDataFolder() . Main::$homefile . $sender->getName() . ".json", Config::JSON);
            $x = $home->getNested($args[0] . ".X");
            $y = $home->getNested($args[0] . ".Y");
            $z = $home->getNested($args[0] . ".Z");
            $world = $home->getNested($args[0] . ".world");
            if ($name === null) {
                $sender->sendMessage($api->getSetting("info") . $api->getLang("homeussage2"));
                return false;
            } else {
                if ($world == null){
                    $message = str_replace("{home}" , $args[0], $api->getLang("homeerror"));
                    $sender->sendMessage($api->getSetting("error") . $message);
                    return false;
                } else {
                    $this->plugin->getServer()->getWorldManager()->loadWorld($world);
                    $sender->teleport(new Position($x , $y , $z, $this->plugin->getServer()->getWorldManager()->getWorldByName($world)));
                    $message = str_replace("{home}" , $args[0], $api->getLang("homesucces"));
                    $sender->sendMessage($api->getSetting("prefix") . $message);
                }
                return false;
            }
        }
        return true;
    }
}