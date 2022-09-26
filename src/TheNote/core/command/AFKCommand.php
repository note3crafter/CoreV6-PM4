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
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use TheNote\core\Main;

class AFKCommand extends Command implements Listener
{
    private $plugin;
    private $afk;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("afk", $config->get("prefix") . $lang->get("afkprefix"), "/afk");
        $this->afk = array();
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
        if (isset($this->afk[strtolower($sender->getName())])) {
            $cfg = new Config($this->plugin->getDataFolder() . Main::$userfile . $sender->getName() . ".json", Config::JSON);
            unset($this->afk[strtolower($sender->getName())]);
            $sender->sendMessage($config->get("afk") . $lang->get("afknowafk"));
            $sender->setImmobile(false);
            $cfg->set($cfg->get("afkmove") == false);
            $cfg->set($cfg->get("afkchat") == false);
            $cfg->set("afk", false);
            $cfg->save();
        } else {
            $cfg = new Config($this->plugin->getDataFolder() . Main::$userfile . $sender->getName() . ".json", Config::JSON);
            $this->afk[strtolower($sender->getName())] = strtolower($sender->getName());
            $sender->sendMessage($config->get("afk") . $lang->get("afknoafk"));
            $sender->setImmobile(true);
            $cfg->set($cfg->get("afkmove") == true);
            $cfg->set($cfg->get("afkchat") == true);
            $cfg->set("afk", true);
            $cfg->save();
        }
        return true;
    }
    public function onQuit(PlayerQuitEvent $event){
        $player = $event->getPlayer();
        $cfg = new Config($this->plugin->getDataFolder() . Main::$userfile . $player->getName() . ".json", Config::JSON);
        $cfg->set($cfg->get("afkmove") == false);
        $cfg->set($cfg->get("afkchat") == false);
        $cfg->save();
    }


    public function onMove(PlayerMoveEvent $event) {
        $player = $event->getPlayer();
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $cfg = new Config($this->plugin->getDataFolder() . Main::$userfile . $player->getName() . ".json", Config::JSON);
        if($cfg->get("afkmove") == true) {
            $player->sendMessage($lang->get("afknomove"));
            $event->cancel();
        }
    }

    public function onChat(PlayerChatEvent $event) {
        $player = $event->getPlayer();
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $cfg = new Config($this->plugin->getDataFolder() . Main::$userfile . $player->getName() . ".json", Config::JSON);
        if($cfg->get("afkchat") == true) {
            $player->sendMessage($lang->get("afknochat"));
            $event->cancel();
        }
    }
}