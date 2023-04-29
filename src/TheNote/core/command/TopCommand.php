<?php

namespace TheNote\core\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class TopCommand extends Command
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("top", $api->getSetting("prefix") . $api->getLang("topprefix"), "/top");
        $this->setPermission("core.command.top");
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
        $sender->sendMessage($api->getSetting("prefix") . $api->getLang("topsucces"));
        $sender->teleport(new Vector3($sender->getPosition()->getX(), $sender->getWorld()->getHighestBlockAt($sender->getPosition()->getFloorX(), $sender->getPosition()->getFloorZ()) + 1, $sender->getPosition()->getZ()));
        return true;
    }
}