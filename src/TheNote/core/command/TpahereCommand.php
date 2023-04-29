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
use TheNote\core\BaseAPI;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;

class TpahereCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("tpahere", $api->getSetting("prefix") . $api->getLang("tpahereprefix"), "/tpahere");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("commandingame"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($api->getSetting("info") . $api->getLang("tpahereusage"));
            return false;
        }
        $target = $api->findPlayer($sender, $args[0]);
        if ($target === $sender){
            $sender->sendMessage($api->getSetting("error") . $api->getLang("tpaherenotyourself"));
            return false;
        }
        if ($target instanceof Player) {
            $this->plugin->setInvite($sender, $target);
            $message = str_replace("{sender}", $sender->getName(), $api->getLang("tpaheretarget"));
            $target->sendMessage($api->getSetting("tpa") . $message);
            $message1 = str_replace("{target}", $target->getName(), $api->getLang("tpaheresender"));
            $sender->sendMessage($api->getSetting("tpa") . $message1);
        } else {
            $sender->sendMessage($api->getSetting("tpa") . $api->getLang("playernotonline"));
        }
        return true;
    }
}