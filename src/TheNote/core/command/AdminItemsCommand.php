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

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class AdminItemsCommand extends Command implements Listener
{

    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("adminitems", $api->getSetting("prefix") . $api->getLang("adminitemsprefix"), "/adminitems" , ["ai", "aitmes"]);
        $this->setPermission("core.command.adminitems");
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args):bool
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
            $sender->sendMessage($api->getSetting("info") . "§eUsage : /adminitems <superbow|explosivbow|explosivegg>");
            return true;
        }
        if ($args[0] == "superbow") {
            if ($sender->hasPermission("core.command.adminitems.superbow")or $sender->getServer()->isOp($sender->getName())) {
                $this->superbow($sender);
                $sender->sendMessage($api->getSetting("prefix") . $api->getLang("adminitems1"));
            } else {
                $sender->sendMessage($api->getSetting("error") . $api->getLang("adminitems2"));
            }
        }
        if ($args[0] == "explosivbow") {
            if ($sender->hasPermission("core.command.adminitems.explosivbow") or $sender->getServer()->isOp($sender->getName())) {
                $this->explosivbow($sender);
                $sender->sendMessage($api->getSetting("prefix") . $api->getLang("adminitems1"));
            } else {
                $sender->sendMessage($api->getSetting("error") . $api->getLang("adminitems2"));
            }
        }
        if ($args[0] == "explosivegg") {
            if ($sender->hasPermission("core.command.adminitems.explosivbow") or $sender->getServer()->isOp($sender->getName())) {
                $this->explodeegg($sender);
                $sender->sendMessage($api->getSetting("prefix") . $api->getLang("adminitems1"));
            } else {
                $sender->sendMessage($api->getSetting("error") . $api->getLang("adminitems2"));
            }
        }
        return true;
    }
    public function superbow(Player $player)
    {
        $sbogen = ItemFactory::getInstance()->get(261, 0, 1);
        $sbogen->setCustomName("§f[§cSuperBow§f]");
        $sbogen->getNamedTag()->setString("custom_data", "super_bow");
        $player->getInventory()->addItem($sbogen);

    }
    public function explosivbow(Player $player)
    {
        $ebow = ItemFactory::getInstance()->get(261, 0, 1);
        $ebow->setCustomName("§f[§cExplosivBow§f]");
        $ebow->getNamedTag()->setString("custom_data", "explode_bow");
        $player->getInventory()->addItem($ebow);

    }
    public function explodeegg(Player $player)
    {
        $egg = ItemFactory::getInstance()->get(344, 0, 16);
        $egg->setCustomName("§f[§cExplosivEgg§f]");
        $egg->getNamedTag()->setString("custom_data", "explode_egg");
        $player->getInventory()->addItem($egg);
    }
}