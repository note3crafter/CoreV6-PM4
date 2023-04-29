<?php

namespace TheNote\core\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class CompassCommand extends Command
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("compass", $api->getSetting("prefix") . $api->getLang("compassprefix"), "/compass");
        $this->setPermission("core.command.compass");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool{
        $api = new BaseAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("commandingame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("nopermission"));
            return false;
        }
        switch($sender->getDirectionPlane()){
            case 0:
                $direction = "south";
                break;
            case 1:
                $direction = "west";
                break;
            case 2:
                $direction = "north";
                break;
            case 3:
                $direction = "east";
                break;
            default:
                $sender->sendMessage($api->getSetting("error") . $api->getLang("compassserror"));
                return false;
                break;
        }
        $message = str_replace("{facing}" ,  $direction , $api->getLang("compasssussces"));
        $sender->sendMessage($api->getSetting("prefix") . $message);
        return true;
    }

}