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
use TheNote\core\BaseAPI;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class FlyCommand extends Command implements Listener
{
	private Main $plugin;

	public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("fly", $api->getSetting("prefix") . $api->getLang("flydprefix"), "/fly");
        $this->setPermission("core.command.fly");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("commandingame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("nopermission"));
            return false;
        }
        if (isset($args[0])) {
            if ($sender->hasPermission("core.command.fly.other")) {
                $victim = $api->findPlayer($sender, $args[0]);
                if ($victim == null) {
                    $sender->sendMessage($api->getSetting("error") . $api->getLang("playernotonline"));
                    return false;
                }
                if ($victim->getAllowFlight() === true) {
                    $victim->setAllowFlight(false);
                    $victim->setFlying(false);
                    $message1 = str_replace("{sender}" , $sender->getNameTag(), $api->getLang("flytargetoff"));
                    $victim->sendMessage($api->getSetting("prefix") . $message1);
                    $message = str_replace("{victim}" , $victim->getName(), $api->getLang("flytargetoff2"));
                    $sender->sendMessage($api->getSetting("prefix") . $message);
                } else {
                    $victim->setAllowFlight(true);
                    $victim->setFlying(true);
                    $message1 = str_replace("{sender}" , $sender->getNameTag(), $api->getLang("flytargeton"));
                    $victim->sendMessage($api->getSetting("prefix") . $message1);
                    $message = str_replace("{victim}" , $victim->getName(), $api->getLang("flytargeton2"));
                    $sender->sendMessage($api->getSetting("prefix") . $message);
                }
                return false;
            } else {
                $sender->sendMessage($api->getSetting("error") . $api->getLang("flytargetnoperm"));
                return false;
            }
        }
        if ($sender->getAllowFlight() === true) {
            $sender->setAllowFlight(false);
            $sender->setFlying(false);
            $sender->sendMessage($api->getSetting("prefix") . $api->getLang("flyoff"));
        } else {
            $sender->setAllowFlight(true);
            $sender->setFlying(true);
            $sender->sendMessage($api->getSetting("prefix") . $api->getLang("flyon"));
        }
        return false;
    }
}