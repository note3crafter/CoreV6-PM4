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
use TheNote\core\Main;

class SetHomeCommand extends Command
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("sethome", $config->get("prefix") . $lang->get("sethomeprefix"), "/sethome <Home>");
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $configs = new Config($this->plugin->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . $lang->get("commandingame"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($config->get("info") . "§cBenutze : /sethome [Homename]");
        }
        $user = new Config($this->plugin->getDataFolder() . Main::$userfile . $sender->getName() . ".json", Config::JSON);
        if ($user->get("homes") === $configs->get("maxhomes")) {
            $sender->sendMessage($config->get("error") . $lang->get("sethomemaxhomes"));
            return true;
        }
        if (isset($args[0])) {
            $x = $sender->getLocation()->getX();
            $y = $sender->getLocation()->getY();
            $z = $sender->getLocation()->getZ();
            $world = $sender->getWorld()->getFolderName();
            $name = $args[0];
            $user = new Config($this->plugin->getDataFolder() . Main::$userfile . $sender->getName() . ".json", Config::JSON);
            $user->set("homes", $user->set("homes") + 1);
            $user->save();
            $home = new Config($this->plugin->getDataFolder() . Main::$homefile . $sender->getName() . ".json", Config::JSON);
            $home->set($name, ["X" => $x, "Y" => $y, "Z" => $z, "world" => $world]);
            $home->save();
            $message = str_replace("{x}", $x, $lang->get("sethomesucces"));
            $message1 = str_replace("{y}", $y , $message);
            $message2 = str_replace("{z}", $z , $message1);
            $message3 = str_replace("{world}", $world, $message2);
            $sender->sendMessage($config->get("prefix") . $message3);
        }
        return true;
    }
}