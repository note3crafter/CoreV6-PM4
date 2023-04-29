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

use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class PayCoinsCommand extends Command
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("paycoins", $api->getSetting("prefix") . $api->getLang("paycoinsprefix"), "/paycoins" , );
        $this->setPermission("core.command.paycoins");
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
        if (empty($args[0])) {
            $sender->sendMessage($api->getSetting("info") . $api->getLang("paycoinsusage"));
            return false;
        }
        if (!is_numeric($args[1])) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("paycoinsnumb"));
            return false;
        }
        $target = $api->findPlayer($sender, $args[0]);
        if ($target instanceof Player){
            $sender->sendMessage($api->getSetting("error") . $api->getLang("paycoinsyouself"));
            return false;
        }
        if ($target == null) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("playernotonline"));
            return false;
        }
        if ($args[1] > $api->getCoins($sender)) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("paycoinsnocoins"));
            return false;
        }
        $api->addCoins($target, (int)$args[1]);
        $api->removeCoins($sender, (int)$args[1]);
        $message = str_replace("{vicim}", $target, $api->getLang("paycoinstarget"));
        $message1 = str_replace("{coins}", $args[1] , $message);
        $sender->sendMessage($api->getSetting("coins") . $message1);
        $message2 = str_replace("{sender}", $sender->getName(), $api->getLang("paycoinssender"));
        $message3 = str_replace("{coins}", $args[1] , $message2);
        $target->sendMessage($api->getSetting("coins") . $message3);
        return true;
    }
}