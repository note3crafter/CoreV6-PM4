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
use TheNote\core\BaseAPI;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use TheNote\core\utils\economy\EconomyManager;

class SetMoneyCommand extends Command implements Listener
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("setmoney", $api->getSetting("prefix") . $api->getLang("setmoneyprefix"), "/setmoney {player} {value}");
        $this->setPermission("core.command.setmoney");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("nopermission"));
            return false;
        }
        if(!isset($args[0])) {
            $sender->sendMessage($api->getSetting("info") . $api->getLang("setmoneyusage"));
            return false;
        }
        if(!isset($args[1])) {
            $sender->sendMessage($api->getSetting("info") . $api->getLang("setmoneyusage"));
            return false;
        }
        if(!is_numeric($args[1])) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("setmoneynumb"));
            return false;
        }
        $target = $api->findPlayer($sender, $args[0]);
        if ($target == null) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("playernotonline"));
            return false;
        }
        $api->setMoney($target, (int)$args[1]);
        $message = str_replace("{target}", $target->getName(), $api->getLang("setmoneysender"));
        $message1 = str_replace("{money}", $args[1] , $message);
        $sender->sendMessage($api->getSetting("money") . $message1);
        $message2 = str_replace("{money}", $args[1], $api->getLang("setmoneytarget"));
        $target->sendMessage($api->getSetting("money") . $message2);
        return true;
    }

}