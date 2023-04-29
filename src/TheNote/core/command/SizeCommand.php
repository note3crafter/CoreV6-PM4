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

class SizeCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("size", $api->getSetting("prefix") . $api->getLang("sizeprefix"), "/size [Zahl] {player}");
        $this->setPermission("core.command.size");
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
            $sender->setScale(1);
            $sender->sendMessage($api->getSetting("prefix") . $api->getLang("sizereset"));
            return false;
        }
        if (isset($args[0])) {
            if (is_numeric($args[0])) {
                if ($args[0] > 10) {
                    $sender->sendMessage($api->getSetting("error") . $api->getLang("sizetohigh"));
                    return true;
                } elseif ($args[0] < 0.05) {
                    $sender->sendMessage($api->getSetting("error") . $api->getLang("sizetolow"));
                    return true;
                }
                $sender->setScale((float)$args[0]);
                $message = str_replace("{size}" , $args[0], $api->getLang("sizesucces"));
                $sender->sendMessage($api->getSetting("prefix") . $message);
                return true;
            } else {
                $sender->sendMessage($api->getSetting("error") . $api->getLang("sizenumb"));
            }
        }
        return true;
    }
}