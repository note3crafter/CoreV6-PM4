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
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\Main;

class ClearCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
		$langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
		$l = $langsettings->get("Lang");
		$lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        parent::__construct("clear", $config->get("prefix") . $lang->get("clearprefix"), "/clear");
        $this->setPermission("core.command.clear");
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
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . $lang->get("nopermission"));
            return false;
        }
        if (isset($args[0])) {
            if ($sender->hasPermission("core.command.clear.other")) {
                $victim = $this->plugin->getServer()->getPlayerExact($args[0]);
                $target = Server::getInstance()->getPlayerExact(strtolower($args[0]));
                if ($target == null) {
                    $sender->sendMessage($config->get("error") . $lang->get("playernotonline"));
                    return false;
                } else {
                    $victim->getInventory()->clearAll();
					$msgplayer = str_replace("{player}" , $sender->getNameTag(), $lang->get("clearplayer"));
					$victim->sendMessage($config->get("prefix") . $msgplayer);
					$msgvictim = str_replace("{victim}" , $victim, $lang->get("clearvictim"));
                    $sender->sendMessage($config->get("prefix") . $msgvictim);
                    return false;
                }
            } else {
                $sender->sendMessage($config->get("error") . $lang->get("clearerror"));
                return false;
            }
        }
        $sender->getInventory()->clearAll();
        $sender->sendMessage($config->get("prefix") . $lang->get("clearcomfirm"));
        return true;
    }
}
