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

use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\entity\Living;
use pocketmine\entity\object\ExperienceOrb;
use pocketmine\entity\object\ItemEntity;
use pocketmine\Server;
use pocketmine\utils\Config;
use ReflectionClass;
use TheNote\core\BaseAPI;
use TheNote\core\Main;
use pocketmine\command\Command;

class ClearlaggCommand extends Command
{
    private $plugin;
    public const PREFERENCE_ITEMS = 0x1;
    public const PREFERENCE_LIVING = 0x2;
    public const PREFERENCE_XPORBS = 0x3;
    public const PREFERENCE_ALL = 0x4;
    private array $preferences = [];

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
		$api = new BaseAPI();
        parent::__construct("clearlagg", $api->getSetting("prefix") . $api->getLang("clearlaggprefix"), "/clearlagg", ["cl", "clagg"]);
        $this->setPermission("core.command.clearlagg");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args):bool
    {
        $api = new BaseAPI();
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("nopermission"));
            return false;
        }
        $this->plugin->clearItems = (bool)(true);
        foreach ($this->plugin->getServer()->getWorldManager()->getWorlds() as $level) {
            foreach ($level->getEntities() as $entity) {
                if ($this->plugin->clearItems && $entity instanceof ItemEntity) {
                    if (!$entity instanceof Human){
                        $entity->flagForDespawn();
                    }
                }
                if ($this->plugin->clearItems && ($entity instanceof Entity)) {
                    if (!$entity instanceof Human){
                        $entity->flagForDespawn();
                    }
                }
            }
        }
        $sender->sendMessage($api->getSetting("prefix") . $api->getLang("clearlaggconfirm"));
        return true;
    }
}