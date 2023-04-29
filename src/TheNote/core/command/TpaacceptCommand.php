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

use pocketmine\player\Player;
use TheNote\core\BaseAPI;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;

class TpaacceptCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("tpaccept", $api->getSetting("prefix") . $api->getLang("tpaacceptprefix"), "/tpaccept");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("commandingame"));
            return false;
        }
        if (!empty($args[0])) {
            $sender->sendMessage($api->getSetting("info") . $api->getLang("tpaacceptusage"));
        } else {
            $this->tpak($sender->getName());
        }
        return true;
    }
    public function tpak(string $name): void
    {
        $api = new BaseAPI();
        $player = $this->plugin->getServer()->getPlayerExact($name);
        if ($this->plugin->getInviteControl($name)) {
            $sender = $this->plugin->getServer()->getPlayerExact($this->plugin->getInvite($name));
            $sender->teleport($player->getLocation()->asPosition());
            unset($this->plugin->invite[$name]);
            $message = str_replace("{player}", $name, $api->getLang("tpaacceptsender"));
            $sender->sendMessage($api->getSetting("tpa") . $message);
        } else {
            $player->sendMessage($api->getSetting("tpa") . $api->getLang("tpaaccepttarget"));
        }
    }
}