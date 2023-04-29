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
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class GiveCoinsCommand extends Command
{
    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("givecoins", $api->getSetting("prefix") . $api->getLang("givecoinsdprefix"), "/givecoins <player> <coins>");
        $this->setPermission("core.command.givecoins");
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
        if (is_numeric($args[0])) {
            $api->addCoins($sender, $args[0]);
            $message = str_replace("{coins}", $args[0], $api->getLang("givecoinssucces"));
            $sender->sendMessage($api->getSetting("money") . $message);
            return true;
        } else {
            if (!is_numeric($args[1])) {
                $sender->sendMessage($api->getSetting("error") . $api->getLang("givecoinsnumb"));
                return false;
            }
            if (count($args) < 2) {
                $sender->sendMessage($api->getSetting("prefix") . $api->getLang("givecoinssusage"));
                return false;
            }
            $target = $api->findPlayer($sender, $args[0]);
            $api->addCoins($target, $args[1]);
            $message = str_replace("{player}", $target->getName(), $api->getLang("givecoinsplayersucces"));
            $message1 = str_replace("{coins}", $args[1], $message);
            $sender->sendMessage($api->getSetting("money") . $message1);
        }
        return true;
    }
}