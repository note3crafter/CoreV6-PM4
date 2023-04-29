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
use TheNote\core\Main;

class ReplyCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("reply", $api->getSetting("prefix") . $api->getLang("replyprefix"), "/reply <message>", ["r"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("commandingame"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($api->getSetting("info") . $api->getLang("replyusage"));
            return false;
        }
        $cfg = new Config($this->plugin->getDataFolder() . Main::$userfile . $args[0] . ".json", Config::JSON);
        /*if ($sender->hasPermission("core.command.nodm.bypass")) {
            return true;
        }*/

        if ($cfg->get("nodm") === true) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("tellmsgblock"));
            return false;
        } else {
            if (!empty($this->plugin->getLastSent($sender->getName()))) {
                $player = $this->plugin->getServer()->getPlayerExact($this->plugin->getLastSent($sender->getName()));
                if ($player instanceof CommandSender) {
                    $message = str_replace("{sender}", $sender->getNameTag(), $api->getLang("replymsgsender"));
                    $message1 = str_replace("{player}", $player->getNameTag(), $message);
                    $sender->sendMessage($api->getSetting("msg") . $message1 . implode(" ", $args));
                    $message2 = str_replace("{player}", $sender->getNameTag(), $api->getLang("replymsgtarget"));
                    $player->sendMessage($api->getSetting("msg") . $message2 . implode(" ", $args));
                    $this->plugin->onMessage($sender, $player);
                    Server::getInstance()->getLogger()->info($api->getSetting("msg") . $message2 . implode(" ", $args));
                    Server::getInstance()->getLogger()->info($api->getSetting("msg") . $message1 . implode(" ", $args));
                } else {
                    $sender->sendMessage($api->getSetting("error") . $api->getLang("playernotonline"));
                }
            } else {
                $sender->sendMessage($api->getSetting("error") . $api->getLang("replaynoplayer"));
            }
        }
        return true;
    }
}