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

class PayMoneyCommand extends Command implements Listener
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("pay", $api->getSetting("prefix") . $api->getLang("paymoneyprefic"), "/pay {player} {value}");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("commandingame"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($api->getSetting("money") . $api->getLang("paymoneyusage"));
            return false;
        }
        if (empty($args[1])) {
            $sender->sendMessage($api->getSetting("money") . $api->getLang("paymoneyusage"));
            return false;
        }
        if (!is_numeric($args[1])) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("paymoneynumb"));
            return false;
        }
        $target = $api->findPlayer($sender, $args[0]);
        if ($target == null) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("playernotonline"));
            return false;
        }
        if ($sender->getName() == $api->findPlayer($sender, $args[0])){
            $sender->sendMessage($api->getSetting("error") . $api->getLang("paymoneyyouself"));
            return false;
        }
        if ($args[1] > $api->getMoney($sender->getName())) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("paymoneynomoney"));
            return false;
        }
        $api->addMoney($target, (int)$args[1]);
        $api->removeMoney($sender, (int)$args[1]);

        $message = str_replace("{victim}", $target->getName(), $api->getLang("paymoneytarget"));
        $message1 = str_replace("{money}", $args[1] , $message);
        $sender->sendMessage($api->getSetting("money") . $message1);
        $message2 = str_replace("{player}", $sender->getName(), $api->getLang("paymoneysender"));
        $message3 = str_replace("{money}", $args[1] , $message2);
        $target->sendMessage($api->getSetting("money") . $message3);
        return true;
    }
}