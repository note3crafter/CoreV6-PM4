<?php

namespace TheNote\core\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;
use TheNote\core\utils\NonSolidBlocks;

class JumpCommand extends Command
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("jump", $api->getSetting("prefix") . $api->getLang("jumpprefix"), "/jump");
        $this->setPermission("core.command.jump");
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

        $block = $sender->getTargetBlock(1000, NonSolidBlocks::NON_SOLID_BLOCKS);
        if ($block === null) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("jumperror"));
            return false;
        }
        if (!$sender->getWorld()->getBlock($block->getPosition()->add(0, 2, 0))->isSolid()) {
            $sender->teleport($block->getPosition()->add(0, 1, 0));
            return true;
        }
        switch ($side = $sender->getDirectionVector()) {
            case 1:
                $side += 3;
                break;
            case 3:
                $side += 2;
                break;
            default:
                break;
        }
        if (!$block->getSide($side)->isSolid()) {
            $sender->teleport($block);
        }
        return true;
    }
}