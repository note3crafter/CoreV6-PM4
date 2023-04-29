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

use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;
use pocketmine\command\Command;

class KickCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("kick", $api->getSetting("prefix") . $api->getLang("kickprefix"), "/kick <spieler> <grund>");
        $this->setPermission("core.command.kick");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("nopermission"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($api->getSetting("info") . $api->getLang("kickusage"));
        }
        if (isset($args[0])) {
            if (empty($args[1])) {
                if ($api->findPlayer($sender, $args[0]) instanceof Player) {
                    $victim = $api->findPlayer($sender, $args[0]);
                    $message = str_replace("{sender}", $sender->getName(), $api->getLang("kicksucces"));
                    $victim->kick($message, false);
                }
            }
            if (isset($args[1])) {
                $victim = $api->findPlayer($sender, $args[0]);
                $message = str_replace("{reason}", $args[1], $api->getLang("kicksucces1"));
                $message1 = str_replace("{sender}", $sender->getName(), $message);
                $victim->kick($message1, false);
            }
        }
        return true;
    }
}

