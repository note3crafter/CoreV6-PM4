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

class PayMoneyCommand extends Command implements Listener
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("pay", $config->get("prefix") . $lang->get("paymoneyprefic"), "/pay {player} {value}");
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
        if (empty($args[0])) {
            $sender->sendMessage($config->get("money") . $lang->get("paymoneyusage"));
            return false;
        }
        if (empty($args[1])) {
            $sender->sendMessage($config->get("money") . $lang->get("paymoneyusage"));
            return false;
        }
        if (!is_numeric($args[1])) {
            $sender->sendMessage($config->get("error") . $lang->get("paymoneynumb"));
            return false;
        }
        $target = Server::getInstance()->getPlayerByPrefix(strtolower($args[0]));
        if ($target instanceof Player){
            $sender->sendMessage($config->get("error") . $lang->get("paymoneyyouself"));
            return false;
        }
        if ($target == null) {
            $sender->sendMessage($config->get("error") . $lang->get("playernotonline"));
            return false;
        }
        if ($args[1] > $money->getNested("money." . $sender->getName())) {
            $sender->sendMessage($config->get("error") . $lang->get("paymoneynomoney"));
            return false;
        }
        $money->setNested("money." . $target->getName(), $money->getNested("money." . $target->getName()) + (int)$args[1]);
        $money->setNested("money." . $sender->getName(), $money->getNested("money." . $sender->getName()) - (int)$args[1]);
        $money->save();
        $message = str_replace("{vicim}", $target->getName(), $lang->get("paymoneytarget"));
        $message1 = str_replace("{money}", $args[1] , $message);
        $sender->sendMessage($config->get("money") . $message1);
        $message2 = str_replace("{sender}", $sender->getName(), $lang->get("paymoneysender"));
        $message3 = str_replace("{money}", $args[1] , $message2);
        $target->sendMessage($config->get("money") . $message3);
        return true;
    }
}