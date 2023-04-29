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

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\Explosion;
use TheNote\core\BaseAPI;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;

class NukeCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("nuke", $api->getSetting("prefix") . $api->getLang("nukeprefix"), "/nuke");
        $this->setPermission("core.command.nuke");
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
            if ($sender->hasPermission("core.command.nuke.other")) {
                $target = $api->findPlayer($sender, $args[0]);
                if ($target == null) {
                    $sender->sendMessage($api->getSetting("error") . $api->getLang("playernotonline"));
                    return false;
                } else {
                    $explosion = new Explosion($sender->getPosition(),100);
                    $explosion->explodeA();
                    $explosion->explodeB();
                    $target->sendMessage($api->getSetting("prefix") . $api->getLang("nuketarget"));
                    $message = str_replace("{victim}", $target->getName(), $api->getLang("nukesender"));
                    $sender->sendMessage($api->getSetting("prefix") . $message);
                    return false;
                }
            } else {
                $sender->sendMessage($api->getSetting("error") . $api->getLang("nukenopermtarget"));
                return false;
            }
        }
        $explosion = new Explosion($sender->getPosition(),100);
        $explosion->explodeA();
        $explosion->explodeB();
        $sender->sendMessage($api->getSetting("prefix") . $api->getLang("nukesucces"));
        return true;
    }
}