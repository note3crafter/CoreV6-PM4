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

class FakeCommand extends Command {

    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("fake", $api->getSetting("prefix") . "§6Mache ein FakeLeave/Join", "/fake", ["f"]);
        $this->setPermission("core.command.fake");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        $playerdata = new Config($this->plugin->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);

        $gruppe = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $sender->getName() . ".json", Config::JSON);
        if (!$sender instanceof Player) {
             $sender->sendMessage($api->getSetting("error") . "§cDiesen Command kannst du nur Ingame benutzen");
             return false;
        }
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("commandingame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("nopermission"));
            return false;
        }
        if ($args[0] == "join") {
            if ($sender->hasPermission("core.command.fake") || $sender->getServer()->isOp($sender->getName())) {
                $all = $this->plugin->getServer()->getOnlinePlayers();
                $prefix = $playerdata->getNested($sender->getName() . ".groupprefix");
                $slots = $api->getSetting("slots");
                $spielername = $gruppe->get("Nickname");
                $stp1 = str_replace("{player}", $spielername, $api->getConfig("Joinmsg"));
                $stp2 = str_replace("{count}", count($all), $stp1);
                $stp3 = str_replace("{slots}", $slots , $stp2);
                $joinmsg = str_replace("{prefix}", $prefix, $stp3);
                $this->plugin->getServer()->broadcastMessage($joinmsg);
            }
        }
        if ($args[0] == "leave") {
            if ($sender->hasPermission("core.command.fake") || $sender->getServer()->isOp($sender->getName())) {
                $all = $this->plugin->getServer()->getOnlinePlayers();
                $prefix = $playerdata->getNested($sender->getName() . ".groupprefix");
                $slots = $api->getSetting("slots");
                $spielername = $gruppe->get("Nickname");
                $stp1 = str_replace("{player}", $spielername, $api->getConfig("Quitmsg"));
                $stp2 = str_replace("{count}", count($all), $stp1);
                $stp3 = str_replace("{slots}", $slots , $stp2);
                $quitmsg = str_replace("{prefix}", $prefix, $stp3);
                $this->plugin->getServer()->broadcastMessage($quitmsg);
            }
        }
        return true;
    }
}
