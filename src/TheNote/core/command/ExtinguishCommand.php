<?php

namespace TheNote\core\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class ExtinguishCommand extends Command
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("extinguish", $api->getSetting("prefix") . $api->getLang("extinguishprefix"), "/top");
        $this->setPermission("core.command.extinguish");
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
        $player = $sender;
        if (isset($args[0])) {
            $target = $api->findPlayer($sender, $args[0]);
            if (!$sender->hasPermission("core.command.extinguish.other")) {
                $sender->sendMessage($api->getSetting("error") . $api->getLang("nopermission"));
                return false;
            } elseif (!($player = $target)) {
                $sender->sendMessage($api->getSetting("error") . $api->getLang("playernotonline"));
                return false;
            }
        }
        $player->extinguish();
        $sender->sendMessage($api->getSetting("prefix") . $api->getLang("extinguishsucces"));
        return true;
    }
}