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

class SetHomeCommand extends Command
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("sethome", $api->getSetting("prefix") . $api->getLang("sethomeprefix"), "/sethome <Home>");
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("commandingame"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($api->getSetting("info") . "§cBenutze : /sethome [Homename]");
        }
        $user = new Config($this->plugin->getDataFolder() . Main::$userfile . $sender->getName() . ".json", Config::JSON);
        if ($user->get("homes") === $api->getConfig("maxhomes")) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("sethomemaxhomes"));
            return true;
        }
        if (isset($args[0])) {
            $user = new Config($this->plugin->getDataFolder() . Main::$userfile . $sender->getName() . ".json", Config::JSON);
            $user->set("homes", $user->set("homes") + 1);
            $user->save();
            $x = $sender->getLocation()->getX();
            $y = $sender->getLocation()->getY();
            $z = $sender->getLocation()->getZ();
            $world = $sender->getWorld()->getFolderName();
            $name = $args[0];
            $home = new Config($this->plugin->getDataFolder() . Main::$homefile . $sender->getName() . ".json", Config::JSON);
            $home->set($name, ["X" => $x, "Y" => $y, "Z" => $z, "world" => $world]);
            $home->save();
            $message = str_replace("{x}", $x, $api->getLang("sethomesucces"));
            $message1 = str_replace("{y}", $y , $message);
            $message2 = str_replace("{z}", $z , $message1);
            $message3 = str_replace("{world}", $world, $message2);
            $sender->sendMessage($api->getSetting("prefix") . $message3);
        }
        return true;
    }
}