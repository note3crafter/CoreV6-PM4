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

class TpadenyCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("tpadeny", $api->getSetting("prefix") . $api->getLang("tpadenyprefix"), "/tpadeny");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("commandingame"));
            return false;
        }
        if (!empty($args[0])) {
            $sender->sendMessage($api->getSetting("info") . $api->getLang("tpadenyusage"));
        } else {
            $this->tpar($sender->getName());
        }
        return true;
    }
    public function tpar($name) : void
    {
        $api = new BaseAPI();
        $player = $this->plugin->getServer()->getPlayerExact($name);
        if($this->plugin->getInviteControl($name)){
            $sender = $this->plugin->getServer()->getPlayerExact($this->plugin->getInvite($name));
            unset($this->plugin->invite[$name]);
            $message = str_replace("{player}", $name, $api->getLang("tpadenysender"));
            $sender->sendMessage($api->getSetting("tpa") . $message);
        } else {
            $player->sendMessage($api->getSetting("tpa") . $api->getLang("tpadenytarget"));
        }
    }
}