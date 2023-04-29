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

class GiveMoneyCommand extends Command implements Listener
{
	private Main $plugin;

	public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("givemoney", $api->getSetting("prefix") . $api->getLang("givemoneyprefix"), "/givemoney {player} {value}");
        $this->setPermission("core.command.givemoney");
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
        $target = $api->findPlayer($sender, $args[0]);
        if ($target == null) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("playernotonline"));
            return false;
        }
        if (!isset($args[0])) {
            $sender->sendMessage($api->getSetting("money") .  $api->getLang("givemoneyusage"));
            return false;
        }
        if (!isset($args[1])) {
            $sender->sendMessage($api->getSetting("money") .  $api->getLang("givemoneyusage"));
            return false;
        }
        if (!is_numeric($args[1])) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("givemoneynumb"));
            return false;
        }
        $api->addMoney($target, (int)$args[1]);
        $message = str_replace("{sender}" , $sender->getName(), $api->getLang("givemoneytarget"));
        $message1 = str_replace("{money}" , $args[1], $message);
        $target->sendMessage($api->getSetting("money") . $message1);
        $message2 = str_replace("{victim}" , $target->getName(), $api->getLang("givemoneysender"));
        $message3 = str_replace("{money}" , $args[1], $message2);
        $sender->sendMessage($api->getSetting("money") . $message3);
        return true;
    }
}