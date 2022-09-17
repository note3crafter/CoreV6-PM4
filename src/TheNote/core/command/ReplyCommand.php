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

class ReplyCommand extends Command
{
    private $plugin;
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("reply", $config->get("prefix") . $lang->get("replyprefix"), "/reply <message>", ["r"]);
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
            $sender->sendMessage($config->get("info") . $lang->get("replyusage"));
        }
        if(!empty($this->plugin->getLastSent($sender->getName()))) {
            $player = $this->plugin->getServer()->getPlayerExact($this->plugin->getLastSent($sender->getName()));
                if($player instanceof CommandSender) {
                    $msg = implode(" ", $args);
                    $sName = $sender->getName();
                    $pName = $player->getName();
                    $message = str_replace("{player}", $pName, $lang->get("replymsg"));
                    $message1 = str_replace("{sender}", $sName , $message);
                    $message2 = str_replace("{msg}", $msg , $message1);
                    $sender->sendMessage($config->get("msg") . $message2);
                    $player->sendMessage($config->get("msg") . $message2);
                    $this->plugin->onMessage($sender, $player);
                }else{
                    $sender->sendMessage($config->get("error") . $lang->get("playernotonline"));
                }
        }else{
            $sender->sendMessage($config->get("error") . $lang->get("replaynoplayer"));
        }
        return true;
    }
}