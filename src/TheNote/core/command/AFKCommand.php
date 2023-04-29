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
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class AFKCommand extends Command implements Listener
{
    private $plugin;
    private $afk;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("afk", $api->getSetting("prefix") . $api->getLang("afkprefix"), "/afk");
        $this->afk = array();
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        $pf = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $sender->getName() . ".json", Config::JSON);
        $groups = new Config($this->plugin->getDataFolder(). Main::$cloud . "groups.yml", Config::YAML);
        $playerdata = new Config($this->plugin->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("commandingame"));
            return false;
        }
        if (isset($this->afk[strtolower($sender->getName())])) {
            $cfg = new Config($this->plugin->getDataFolder() . Main::$userfile . $sender->getName() . ".json", Config::JSON);
            unset($this->afk[strtolower($sender->getName())]);
            $sender->sendMessage($api->getSetting("afk") . $api->getLang("afknoafk"));
            $sender->setImmobile(false);
            $cfg->set("afkmove", false);
            $cfg->set("afk", false);
            $cfg->save();
            $playergroup = $playerdata->getNested($sender->getName() . ".group");
            $nametag = str_replace("{name}", $pf->get("Nickname"), $groups->getNested("Groups.{$playergroup}.nametag"));
            $displayname = str_replace("{name}", $pf->get("Nickname"), $groups->getNested("Groups.{$playerdata->getNested($sender->getName().".group")}.displayname"));
            $sender->setDisplayName($displayname);
            $sender->setNameTag($nametag);
        } else {
            $cfg = new Config($this->plugin->getDataFolder() . Main::$userfile . $sender->getName() . ".json", Config::JSON);
            $this->afk[strtolower($sender->getName())] = strtolower($sender->getName());
            $sender->sendMessage($api->getSetting("afk") . $api->getLang("afknowafk"));
            $sender->setImmobile(true);
            $cfg->set("afkmove", true);
            $cfg->set("afk", true);
            $cfg->save();
            $playergroup = $playerdata->getNested($sender->getName() . ".group");
            $nametag = str_replace("{name}", $pf->get("Nickname"), $groups->getNested("Groups.{$playergroup}.nametag"));
            $displayname = str_replace("{name}", $pf->get("Nickname"), $groups->getNested("Groups.{$playerdata->getNested($sender->getName().".group")}.displayname"));
            $sender->setDisplayName("§f[§6AFK§f] " . $displayname);
            $sender->setNameTag("§f[§6AFK§f] " . $nametag);
        }
        return true;
    }
    public function onQuit(PlayerQuitEvent $event){
        $player = $event->getPlayer();
        $playerdata = new Config($this->plugin->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        $pf = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $player->getName() . ".json", Config::JSON);
        $groups = new Config($this->plugin->getDataFolder(). Main::$cloud . "groups.yml", Config::YAML);
        $cfg = new Config($this->plugin->getDataFolder() . Main::$userfile . $player->getName() . ".json", Config::JSON);
        $cfg->set("afkmove", false);
        $cfg->set("afk", false);
        $cfg->save();
        $playergroup = $playerdata->getNested($player->getName() . ".group");
        $nametag = str_replace("{name}", $pf->get("Nickname"), $groups->getNested("Groups.{$playergroup}.nametag"));
        $displayname = str_replace("{name}", $pf->get("Nickname"), $groups->getNested("Groups.{$playerdata->getNested($player->getName().".group")}.displayname"));
        $player->setDisplayName($displayname);
        $player->setNameTag($nametag);
        --Main::$afksesion[$player->getName()];
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
}