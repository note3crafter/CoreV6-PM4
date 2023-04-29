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

use TheNote\core\BaseAPI;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class KickallCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("kickall", $api->getSetting("prefix") . $api->getLang("kickallprefix"), "/kickall");
        $this->setPermission("core.command.kickall");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("commandingame"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($api->getSetting("info") . $api->getLang("kickallusage"));
        }
        if (isset($args[0])) {
            $onlinePlayers = $this->plugin->getServer()->getOnlinePlayers();
            if ($sender->hasPermission("core.command.kickall")) {
                foreach ($this->plugin->getServer()->getOnlinePlayers() as $players) {
                    $name = $sender->getDisplayName();
                    if (count($onlinePlayers) === 0 || (count($onlinePlayers) === 1)) {
                        $sender->sendMessage($api->getSetting("error") . $api->getLang("kickallnoonline"));
                    } elseif ($players !== $sender) {
                        $message = str_replace("{reason}", $args[0], $api->getLang("kickallsucces"));
                        $players->kick($api->getSetting("info") . $message, false);
                        $message1 = str_replace("{sender}", $name . $api->getLang("kickallbc"));
                        $this->plugin->getServer()->broadcastMessage($api->getSetting("info") . $message1);
                    }
                }
            }
        }
        return true;
    }
}