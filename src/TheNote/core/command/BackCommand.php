<?php

namespace TheNote\core\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\world\Position;
use TheNote\core\Main;

class BackCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("back", $config->get("prefix") . $lang->get("backprefix"), "/back");
        $this->setPermission("core.command.back");
    }

    public function execute(CommandSender $sender, string $commandlabel, array $args): bool
    {
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $back = new Config($this->plugin->getDataFolder() . Main::$backfile . "Back.json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);


        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . $lang->get("commandingame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . $lang->get("nopermission"));
            return false;
        }
        if ($back->exists($sender->getName())) {
            $pos = explode(" ", $back->get($sender->getName()));
            $x = (int)$pos[0];
            $y = (int)$pos[1];
            $z = (int)$pos[2];
            $level = $this->plugin->getServer()->getWorldManager()->getWorldByName($pos[3]);
            $sender->teleport(new Position($x, $y, $z, $level));
            $sender->sendMessage($config->get("info") . $lang->get("backmessage"));
        }
        return true;
    }
}