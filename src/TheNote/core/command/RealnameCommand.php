<?php

namespace TheNote\core\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class RealnameCommand extends Command
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("realname", $api->getSetting("prefix") . $api->getLang("realnameprefix"), "/realname");
        $this->setPermission("core.command.realname");
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
        $target = $api->findPlayer($sender, $args[0]);
        if ($target == null) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("playernotonline"));
            return false;
        }
        $message = str_replace("{target}" , $sender->getDisplayName(), $api->getLang("realnamesucces"));
        $message1 = str_replace("{targetname}" , (str_ends_with($sender->getName(), "s") ? "'" : "'s"), $message);
        $sender->sendMessage($api->getSetting("prefix") . $message1);
        return true;
    }

}
