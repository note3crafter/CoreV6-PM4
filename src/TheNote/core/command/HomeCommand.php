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
use TheNote\core\Main;

class HomeCommand extends Command
{
	private Main $plugin;

	public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("home", $config->get("prefix") . $lang->get("homeprefix"), "/home <home>");
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . $lang->get("commandingame"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($config->get("info") . $lang->get("homeussage"));
        }
        if (isset($args[0])) {
            $name = $args[0];
            $home = new Config($this->plugin->getDataFolder() . Main::$homefile . $sender->getName() . ".json", Config::JSON);
            $x = $home->getNested($args[0] . ".X");
            $y = $home->getNested($args[0] . ".Y");
            $z = $home->getNested($args[0] . ".Z");
            $world = $home->getNested($args[0] . ".world");
            if ($name === null) {
                $sender->sendMessage($config->get("info") . $lang->get("homeussage2"));
                return false;
            } else {
                if ($world == null){
                    $message = str_replace("{home}" , $args[0], $lang->get("homeerror"));
                    $sender->sendMessage($config->get("error") . $message);
                    return false;
                } else {
                    $this->plugin->getServer()->getWorldManager()->loadWorld($world);
                    $sender->teleport(new Position($x , $y , $z, $this->plugin->getServer()->getWorldManager()->getWorldByName($world)));
                    $message = str_replace("{home}" , $args[0], $lang->get("homesucces"));
                    $sender->sendMessage($config->get("prefix") . $message);
                }
                return false;
            }
        }
        return true;
    }
}