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

use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\Server;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;

class GiveMoneyCommand extends Command implements Listener
{
	private Main $plugin;

	public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("givemoney", $config->get("prefix") . $lang->get("givemoneyprefix"), "/givemoney {player} {value}");
        $this->setPermission("core.command.givemoney");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $money = new Config($this->plugin->getDataFolder() . Main::$cloud . "Money.yml", Config::YAML);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . $lang->get("commandingame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . $lang->get("nopermission"));
            return false;
        }
        if (!isset($args[0])) {
            $sender->sendMessage($config->get("money") .  $lang->get("givemoneyusage"));
            return false;
        }
        if (!isset($args[1])) {
            $sender->sendMessage($config->get("money") .  $lang->get("givemoneyusage"));
            return false;
        }
        if (!is_numeric($args[1])) {
            $sender->sendMessage($config->get("error") . $lang->get("givemoneynumb"));
            return false;
        }
        $target = Server::getInstance()->getPlayerExact(strtolower($args[0]));
        if ($target == null) {
            $sender->sendMessage($config->get("error") . $lang->get("playernotonline"));
            return false;
        }
        $old = $money->getNested("money." . $target->getName());
        $money->setNested("money." . $target->getName(), $old + (int)$args[1]);
        $money->save();
        $message = str_replace("{sender}" , $sender->getName(), $lang->get("givemoneytarget"));
        $message1 = str_replace("{money}" , $args[1], $message);
        $target->sendMessage($config->get("money") . $message1);
        $message2 = str_replace("{victim}" , $target->getName(), $lang->get("givemoneysender"));
        $message3 = str_replace("{money}" , $args[1], $message2);
        $sender->sendMessage($config->get("money") . $message3);
        return true;
    }
}