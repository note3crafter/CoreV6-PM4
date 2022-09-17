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

use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class FlyCommand extends Command implements Listener
{
	private Main $plugin;

	public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("fly", $config->get("prefix") . $lang->get("flydprefix"), "/fly");
        $this->setPermission("core.command.fly");
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
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . $lang->get("nopermission"));
            return false;
        }
        if (isset($args[0])) {
            if ($sender->hasPermission("core.command.fly.other")) {
                $victim = $this->plugin->getServer()->getPlayerExact($args[0]);
                $target = Server::getInstance()->getPlayerExact(strtolower($args[0]));
                if ($target == null) {
                    $sender->sendMessage($config->get("error") . $lang->get("playernotonline"));
                    return false;
                }
                if ($victim->getAllowFlight() === true) {
                    $victim->setAllowFlight(false);
                    $victim->setFlying(false);
                    $message1 = str_replace("{sender}" , $sender->getNameTag(), $lang->get("flytargetoff"));
                    $target->sendMessage($config->get("prefix") . $message1);
                    $message = str_replace("{victim}" , $victim->getName(), $lang->get("flytargetoff2"));
                    $victim->sendMessage($config->get("prefix") . $message);
                } else {
                    $victim->setAllowFlight(true);
                    $victim->setFlying(true);
                    $message1 = str_replace("{sender}" , $sender->getNameTag(), $lang->get("flytargeton"));
                    $target->sendMessage($config->get("prefix") . $message1);
                    $message = str_replace("{victim}" , $victim->getName(), $lang->get("flytargeton2"));
                    $victim->sendMessage($config->get("prefix") . $message);
                }
                return false;
            } else {
                $sender->sendMessage($config->get("error") . $lang->get("flytargetnoperm"));
                return false;
            }
        }
        if ($sender->getAllowFlight() === true) {
            $sender->setAllowFlight(false);
            $sender->setFlying(false);
            $sender->sendMessage($config->get("prefix") . $lang->get("flyoff"));
        } else {
            $sender->setAllowFlight(true);
            $sender->setFlying(true);
            $sender->sendMessage($config->get("prefix") . $lang->get("flyon"));
        }
        return false;
    }
}