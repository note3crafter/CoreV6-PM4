<?php

namespace TheNote\core\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use TheNote\core\Main;

class SetHubCommand extends Command
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("sethub", $config->get("prefix") . $lang->get("sethubprefix"), "/sethub");
        $this->setPermission("core.command.sethub");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $cfg = new Config($this->plugin->getDataFolder() . Main::$setup . "Config" . ".yml", Config::YAML);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . $lang->get("commandingame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . $lang->get("nopermission"));
            return false;
        }
        $level = $sender->getWorld()->getFolderName();
        $cfg->set("Defaultworld", $cfg->get("Defaultworld", $level));
        $cfg->save();
        $x = $sender->getLocation()->getX();
        $y = $sender->getLocation()->getY();
        $z = $sender->getLocation()->getZ();
        $world = $sender->getWorld()->getFolderName();

        $warp = new Config($this->plugin->getDataFolder() . Main::$cloud . "warps.json", Config::JSON);
        $warp->set("hub", ["X" => $x, "Y" => $y, "Z" => $z, "world" => $world]);
        $warp->save();
        $message = str_replace("{x}", $x, $lang->get("sethubsucces"));
        $message1 = str_replace("{y}", $y , $message);
        $message2 = str_replace("{z}", $z , $message1);
        $message3 = str_replace("{world}", $world, $message2);
        $sender->sendMessage($config->get("prefix") . $message3);

        return true;
    }
}