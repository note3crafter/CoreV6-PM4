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

use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\Server;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;

class KreativCommand extends Command
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        parent::__construct("gmc", $config->get("prefix") . $lang->get("creativeprefix"), "/gmc", ["creative", "gm1"]);
        $this->setPermission("core.command.creativ");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): int
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings.json", Config::JSON);
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . $lang->get("commandingame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . $lang->get("nopermission"));
            return false;
        }
        if (isset($args[0])) {
            if ($sender->hasPermission("core.command.creativ.other")) {
                $victim = $this->plugin->getServer()->getPlayerExact($args[0]);
                $target = Server::getInstance()->getPlayerExact(strtolower($args[0]));
                if ($target == null) {
                    $sender->sendMessage($config->get("error") . $lang->get("playernotonline"));
                    return false;
                } else {
                    $victim->setGamemode(GameMode::CREATIVE());
                    $cfgmsg = str_replace("{victim}", $victim->getName(), $lang->get("creativetarget2"));
                    $victim->sendMessage($config->get("prefix") . $lang->get("creativetarget1"));
                    $sender->sendMessage($config->get("prefix") . $cfgmsg);
                    return false;
                }
            } else {
                $sender->sendMessage($config->get("error") . $lang->get("creativenopermtarget"));
                return false;
            }
        }
        $sender->setGamemode(GameMode::CREATIVE()) ;
        $sender->sendMessage($config->get("prefix") . $lang->get("creativesender"));
        return true;
    }
}