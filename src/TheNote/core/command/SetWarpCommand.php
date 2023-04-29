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

class SetWarpCommand extends Command
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("setwarp", $api->getSetting("prefix") . $api->getLang("setwarpprefix"), "/setwarp");
        $this->setPermission("core.command.setwarp");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        $warp = new Config($this->plugin->getDataFolder() . Main::$cloud . "warps.json", Config::JSON);

        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("commandingame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("nopermission"));
            return false;
        }

        if (isset($args[0])) {
            $x = $sender->getLocation()->getX();
            $y = $sender->getLocation()->getY();
            $z = $sender->getLocation()->getZ();
            $world = $sender->getWorld()->getFolderName();
            $name = $args[0];

            $warp->set($name, ["X" => $x, "Y" => $y, "Z" => $z, "world" => $world]);
            $warp->save();
            $message = str_replace("{x}", $x, $api->getLang("setwarpsucces"));
            $message1 = str_replace("{y}", $y, $message);
            $message2 = str_replace("{z}", $z, $message1);
            $message3 = str_replace("{world}", $world, $message2);
            $sender->sendMessage($api->getSetting("prefix") . $message3);
            return true;
        }
        if (!is_numeric($args[1])) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("setwarpnumb"));
            return false;
        } else {
            $x = $sender->getLocation()->getX();
            $y = $sender->getLocation()->getY();
            $z = $sender->getLocation()->getZ();
            $world = $sender->getWorld()->getFolderName();
            $name = $args[0];
            $warp = new Config($this->plugin->getDataFolder() . Main::$cloud . "warps.json", Config::JSON);
            $warp->set($name, ["X" => $x, "Y" => $y, "Z" => $z, "world" => $world, "gamemode" => (int)$args[1]]);
            $warp->save();
            $message = str_replace("{x}", $x, $api->getLang("setwarpsuccesgm"));
            $message1 = str_replace("{y}", $y, $message);
            $message2 = str_replace("{z}", $z, $message1);
            $message3 = str_replace("{world}", $world, $message2);
            $message4 = str_replace("{gamemode}", $args[1], $message3);
            $sender->sendMessage($api->getSetting("prefix") . $message4);
            return true;
        }
    }
}