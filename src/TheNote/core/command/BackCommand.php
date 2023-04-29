<?php

namespace TheNote\core\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\world\Position;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class BackCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("back", $api->getSetting("prefix") . $api->getLang("backprefix"), "/back");
        $this->setPermission("core.command.back");
    }

    public function execute(CommandSender $sender, string $commandlabel, array $args): bool
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
        if ($api->getBackExist($sender->getName())) {
            $pos = explode(" ", $api->getBack($sender->getName()));
            $x = (int)$pos[0];
            $y = (int)$pos[1];
            $z = (int)$pos[2];
            $level = $this->plugin->getServer()->getWorldManager()->getWorldByName($pos[3]);
            $sender->teleport(new Position($x, $y, $z, $level));
            $sender->sendMessage($api->getSetting("prefix") . $api->getLang("backmessage"));
        }
        return true;
    }
}