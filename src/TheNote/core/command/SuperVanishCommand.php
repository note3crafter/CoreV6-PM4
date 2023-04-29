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

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class SuperVanishCommand extends Command
{
    public static $vanished = [];
    private static SuperVanishCommand $instance;
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("supervanish", $api->getSetting("prefix") . $api->getLang("supervanishprefix"), "/supervanish", ["sv"]);
        $this->setPermission("core.command.supervanish");
    }
    public static function getInstance() : self {
        return self::$instance;
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        $playerdata = new Config($this->plugin->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        $gruppe = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $sender->getName() . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("commandingame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("nopermission"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($api->getSetting("info") . $api->getLang("supervanishusage"));
            return false;
        }
        if (isset($args[0])) {
            if ($args[0] == "on") {
                $sender->sendMessage($api->getSetting("prefix") . $api->getLang("supervanishon"));
                self::$vanished[$sender->getName()] = $sender;
                $all = $this->plugin->getServer()->getOnlinePlayers();
                $prefix = $playerdata->getNested($sender->getName() . ".groupprefix");
                $slots = $api->getSetting("slots");
                $spielername = $gruppe->get("Nickname");
                $stp1 = str_replace("{player}", $spielername, $api->getConfig("Quitmsg"));
                $stp2 = str_replace("{count}", count($all), $stp1);
                $stp3 = str_replace("{slots}", $slots , $stp2);
                $quitmsg = str_replace("{prefix}", $prefix, $stp3);
                $this->plugin->getServer()->broadcastMessage($quitmsg);
                //$sender->getServer()->removePlayerListData($sender->getUniqueId());
                $sender->getServer()->removeOnlinePlayer($sender);

                foreach (Server::getInstance()->getOnlinePlayers() as $player) {
                    assert(true);

                    if (!$player->hasPermission("core.command.supervanish.see")) {
                        $player->hidePlayer($sender);
                    }
                }
            }
            if ($args[0] == "off") {
                $sender->sendMessage($api->getSetting("prefix") . $api->getLang("supervanishoff"));
                unset(self::$vanished[$sender->getName()]);

                assert(true);
                $all = $this->plugin->getServer()->getOnlinePlayers();
                $prefix = $playerdata->getNested($sender->getName() . ".groupprefix");
                $slots = $api->getSetting("slots");
                $spielername = $gruppe->get("Nickname");
                $stp1 = str_replace("{player}", $spielername, $api->getConfig("Joinmsg"));
                $stp2 = str_replace("{count}", count($all), $stp1);
                $stp3 = str_replace("{slots}", $slots , $stp2);
                $joinmsg = str_replace("{prefix}", $prefix, $stp3);
                $this->plugin->getServer()->broadcastMessage($joinmsg);
                foreach (Server::getInstance()->getOnlinePlayers() as $player) {
                    assert(true);
                    $player->showPlayer($sender);
                }
            }
        }
        return true;
    }
    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        $name = $player->getName();

        if(isset(self::$vanished[$name])) {
            $event->setJoinMessage(null);
        }
    }

    public function onQuit(PlayerQuitEvent $event) {
        $player = $event->getPlayer();
        $name = $player->getName();

        if(isset(self::$vanished[$name])) {
            $event->setQuitMessage(null);
        }
    }
}
