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
use TheNote\core\BaseAPI;
use TheNote\core\events\PlayerBurnEvent;
use TheNote\core\Main;

class BurnCommand extends Command
{

    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("burn", $api->getSetting("prefix") . $api->getLang("burnprefix"), "/burn");
        $this->setPermission("core.command.burn");
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
            return true;
        }
        if (empty($args[0])) {
            $sender->sendMessage($api->getSetting("info") . $api->getLang("burnusage"));
            return true;
        }
        if (isset($args[0])) {
            $target = $api->findPlayer($sender, $args[0]);
            if ($target == null) {
                $sender->sendMessage($api->getSetting("error") . $api->getLang("playernotonline"));
                return true;
            }
            $player = $target;
        } else {
            $player = $sender;
        }
        if (!isset($args[1])) {
            $time = 10;
        } elseif (is_numeric($args[0]) >= 0) {
            $time = floor(abs($args[1]));
        } else {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("burnseconds"));
            return true;
        }
        $ev = new PlayerBurnEvent($player, $sender, $time);
        if ($ev->isCancelled()) {
            return true;
        }
        $player->setOnFire($ev->getSeconds());
        if ($player === $sender) {
            $cfgmsg = str_replace("{seconds}", $ev->getSeconds(), $api->getLang("burnyourself"));
            $sender->sendMessage($api->getSetting("info") . $cfgmsg);
        } else {
            $stp1 = str_replace("{seconds}", $ev->getSeconds(), $api->getLang("burnmessage"));
            $msg = str_replace("{player}" , $player->getName(), $stp1);
            $sender->sendMessage($api->getSetting("prefix") . $msg);
        }
        return true;
    }
}