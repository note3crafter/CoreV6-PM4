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
use pocketmine\utils\Config;
use TheNote\core\Main;

class TellCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("tell", $config->get("prefix") . $lang->get("tellprefix"), "/tell <Spieler> <Nachrricht>", ["msg", "whisper", "w"]);
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . $lang->get("commandingame"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($config->get("info") . $lang->get("tellusage"));
            return false;
        }
        $player = $sender->getServer()->getPlayerExact(strtolower($args[0]));
        $cfg = new Config($this->plugin->getDataFolder() . Main::$userfile . $args[0] . ".json", Config::JSON);
        unset($args[0]);
        if ($player == null) {
            $sender->sendMessage($config->get("error") . $lang->get("playernotonline"));
            return false;
        }
        $stats = new Config($this->plugin->getDataFolder() . Main::$statsfile . $player->getName() . ".json", Config::JSON);
        $vote = new Config($this->plugin->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        if ($vote->get("Mindestvotes") === true) {
            if ($stats->get("votes") < 1) {
                $message = str_replace("{votelink}", $vote->get("votelink"), $lang->get("tellvote"));
                $player->sendMessage($config->get("error") . $message);
                return false;
            }
        }
        if ($player === $sender) {
            $sender->sendMessage($config->get("error") . $lang->get("tellnotyou"));
            return false;
        }
        if ($cfg->get("nodm") === true) {
            $sender->sendMessage($config->get("error") . $lang->get("tellmsgblock"));
            return false;
        } else {
            if ($player instanceof Player) {
                $message = str_replace("{sender}", $sender->getNameTag(), $lang->get("tellsuccessender"));
                $message1 = str_replace("{player}", $player->getNameTag() , $message);
                $sender->sendMessage($config->get("msg") . $message1 . implode(" ", $args));
                $message2 = str_replace("{sender}", $sender->getName(), $lang->get("tellsuccestarget"));
                $player->sendMessage($config->get("msg") . $message2 . implode(" ", $args));
                $this->plugin->onMessage($sender, $player);
            }
        }
        return true;
    }
}