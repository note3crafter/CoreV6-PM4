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

use pocketmine\block\BrownMushroom;
use pocketmine\block\RedMushroom;
use pocketmine\block\Sapling;
use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerEvent;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;
use pocketmine\world\generator\object\BirchTree;
use pocketmine\world\generator\object\JungleTree;
use pocketmine\world\generator\object\OakTree;
use pocketmine\world\generator\object\SpruceTree;
use pocketmine\world\Position;
use TheNote\core\BaseAPI;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use TheNote\core\server\generators\normal\object\SwampTree;

class TreeCommand extends Command
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("tree", $api->getSetting("prefix") . $api->getLang("treeprefix"), "/tree", ["baum"]);
        $this->setPermission("core.command.tree");
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
            $sender->sendMessage($api->getSetting("info") . $api->getLang("treeusage"));
            return true;
        }

        $object = null;
        switch (strtolower($args[0])) {
            case "oak":
                $object = new OakTree;
                break;
            case "birch":
                $object = new BirchTree;
                break;
            case "jungle":
                $object = new JungleTree;
                break;
            case "spruce":
                $object = new SpruceTree;
                break;
            case "redmushroom":
                $object = new RedMushroom();
                break;
            case "brownmushroom":
                $object = new BrownMushroom();
                break;
            case "swamp":
                $object = new SwampTree();
                break;
        }
        if($object === null) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("treeerror"));
            return false;
        }
        $object->getBlockTransaction($sender->getWorld(), $sender->getPosition()->getFloorX(), $sender->getPosition()->getFloorY(), $sender->getPosition()->getFloorZ(), new Random())?->apply();
        $message = str_replace("{tree}", $args[0], $api->getLang("treesucces"));
        $sender->sendMessage($api->getSetting("prefix") . $message);
        return true;
    }
}
