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
use pocketmine\utils\Config;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class GiveCoinsCommand extends Command
{
	private Main $plugin;

	public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("givecoins", $config->get("prefix") . $lang->get("givecoinsdprefix"), "/givecoins <player> <coins>");
        $this->setPermission("core.command.givecoins");
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
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . $lang->get("nopermission"));
            return false;
        }
        if (!is_numeric($args[1])) {
            $sender->sendMessage($config->get("error") . $lang->get("givecoinsnumb"));
            return false;
        }
        if(count($args) < 2){
            $sender->sendMessage($config->get("prefix") . $lang->get("givecoinssusage"));
            return false;
        }
        if($this->plugin->getServer()->getPlayerExact($args[0])){
            $player = $this->plugin->getServer()->getPlayerExact($args[0]);
            $coins = new Config($this->plugin->getDataFolder() . Main::$userfile . $player->getName() . ".json", Config::JSON);
            $coins->set("coins", $coins->get("coins") + $args[1]);
            $coins->save();
            $message = str_replace("{player}" , $player->getName(), $lang->get("givecoinssucces"));
            $message1 = str_replace("{coins}" , $args[1], $message);
            $sender->sendMessage($config->get("info") . $message1);
        }else{
            $sender->sendMessage($config->get("error") . $lang->get("playernotfound"));
            return false;
        }
        return true;
    }
}