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

use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\types\LevelSoundEvent;
use pocketmine\player\Player;
use pocketmine\Server;
use TheNote\core\BaseAPI;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;

class FeedCommand extends Command
{

	private Main $plugin;

	public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("feed", $api->getSetting("prefix") . $api->getLang("feedprefix"), "/feed");
        $this->setPermission("core.command.feed");
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
        if (isset($args[0])) {
            if ($sender->hasPermission("core.command.feed.other")) {
                $victim = $api->findPlayer($sender, $args[0]);
                if ($victim == null) {
                    $sender->sendMessage($api->getSetting("error") . $api->getLang("playernotonline"));
                    return false;
                } else {
                    $sender->getHungerManager()->setFood(20);
                    $message = str_replace("{sender}" , $sender->getNameTag(), $api->getLang("feedtargetsucces"));
                    $victim->sendMessage($api->getSetting("prefix") . $message);
                    $message1 = str_replace("{victim}" , $victim->getName(), $api->getLang("feedtargetsucces2"));
                    $sender->sendMessage($api->getSetting("prefix") . $message1);
                    return false;
                }
            } else {
                $sender->sendMessage($api->getSetting("error") . $api->getLang("feedtargetnoperm"));
                return false;
            }
        }
        $sender->getHungerManager()->setFood(20);
        $sender->sendMessage($api->getSetting("prefix") . $api->getLang("feedsucces"));
        return false;
    }
}
