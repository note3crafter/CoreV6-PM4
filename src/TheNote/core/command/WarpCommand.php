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

class WarpCommand extends Command
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("warp", $config->get("prefix") . $lang->get("warpprefix"), "/warp");
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
            $sender->sendMessage($config->get("info") . $lang->get("warpusage"));
        }
        if (isset($args[0])) {
            $name = $args[0];
            $warp = new Config($this->plugin->getDataFolder() . Main::$cloud . "warps.json", Config::JSON);
            $x = $warp->getNested($args[0] . ".X");
            $y = $warp->getNested($args[0] . ".Y");
            $z = $warp->getNested($args[0] . ".Z");
            $world = $warp->getNested($args[0] . ".world");
            if ($name === null) {
                $sender->sendMessage($config->get("info") . $lang->get("warpinfo"));
                return false;
            } else {
                if ($world == null) {
                    $message = str_replace("{warp}", $args[0], $lang->get("warpnotexicst"));
                    $sender->sendMessage($config->get("error") . $message);
                    return false;
                } else {
                    $this->plugin->getServer()->getWorldManager()->loadWorld($world);
					$sender->teleport(new Position($x , $y , $z, $this->plugin->getServer()->getWorldManager()->getWorldByName($world)));
                    $message = str_replace("{warp}", $args[0], $lang->get("warpsucces"));
                    $sender->sendMessage($config->get("prefix") . $message);
                }
                return false;
            }
        }
        return true;
    }
}