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
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class TellCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("tell", $api->getSetting("prefix") . $api->getLang("tellprefix"), "/tell <Spieler> <Nachrricht>", ["msg", "whisper", "w"]);
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("commandingame"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($api->getSetting("info") . $api->getLang("tellusage"));
            return false;
        }
        $player = $api->findPlayer($sender, $args[0]);
        $cfg = new Config($this->plugin->getDataFolder() . Main::$userfile . $args[0] . ".json", Config::JSON);
        unset($args[0]);
        if ($player == null) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("playernotonline"));
            return false;
        }
        $stats = new Config($this->plugin->getDataFolder() . Main::$statsfile . $player->getName() . ".json", Config::JSON);
        $vote = new Config($this->plugin->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        if ($vote->get("Mindestvotes") === true) {
            if ($stats->get("votes") < 1) {
                $message = str_replace("{votelink}", $vote->get("votelink"), $api->getLang("tellvote"));
                $player->sendMessage($api->getSetting("error") . $message);
                return false;
            }
        }
        if ($player === $sender) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("tellnotyou"));
            return false;
        }
        /*if (!$sender->hasPermission("core.command.nodm.bypass")) {

            return true;
        }*/
        if ($cfg->get("nodm") === true) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("tellmsgblock"));
            return false;
        }
        if ($player instanceof Player) {
            $message = str_replace("{sender}", $sender->getNameTag(), $api->getLang("tellsuccessender"));
            $message1 = str_replace("{player}", $player->getNameTag(), $message);
            $sender->sendMessage($api->getSetting("msg") . $message1 . implode(" ", $args));
            $message2 = str_replace("{player}", $sender->getNameTag(), $api->getLang("tellsuccestarget"));
            $player->sendMessage($api->getSetting("msg") . $message2 . implode(" ", $args));
            $this->plugin->onMessage($sender, $player);
            Server::getInstance()->getLogger()->info($api->getSetting("msg") . $message2 . implode(" ", $args));
            Server::getInstance()->getLogger()->info($api->getSetting("msg") . $message1 . implode(" ", $args));

        }
        return true;
    }
}