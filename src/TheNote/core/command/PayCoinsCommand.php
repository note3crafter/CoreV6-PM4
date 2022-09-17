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
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class PayCoinsCommand extends Command
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("paycoins", $config->get("prefix") . $lang->get("paycoinsprefix"), "/paycoins" , );
        $this->setPermission("core.command.paycoins");
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
        if (empty($args[0])) {
            $sender->sendMessage($config->get("info") . $lang->get("paycoinsusage"));
            return false;
        }
        if (!is_numeric($args[1])) {
            $sender->sendMessage($config->get("error") . $lang->get("paycoinsnumb"));
            return false;
        }
        $target = Server::getInstance()->getPlayerByPrefix(strtolower($args[0]));
        if ($target instanceof Player){
            $sender->sendMessage($config->get("error") . $lang->get("paycoinsyouself"));
            return false;
        }
        if ($target == null) {
            $sender->sendMessage($config->get("error") . $lang->get("playernotonline"));
            return false;
        }
        $coins = new Config($this->plugin->getDataFolder() . Main::$userfile . $sender->getName() . ".json", Config::JSON);
        $coinst = new Config($this->plugin->getDataFolder() . Main::$userfile . $target . ".json", Config::JSON);
        if ($args[1] > $coins->get("coins")) {
            $sender->sendMessage($config->get("error") . $lang->get("paycoinsnocoins"));
            return false;
        }
        $coinst->set("coins", $coins->get("coins") + (int)$args[1]);
        $coins->set("coins", $coins->get("coins") - (int)$args[1]);
        $coins->save();
        $message = str_replace("{vicim}", $target, $lang->get("paycoinstarget"));
        $message1 = str_replace("{coins}", $args[1] , $message);
        $sender->sendMessage($config->get("coins") . $message1);
        $message2 = str_replace("{sender}", $sender->getName(), $lang->get("paycoinssender"));
        $message3 = str_replace("{coins}", $args[1] , $message2);
        $target->sendMessage($config->get("coins") . $message3);
        return true;
    }
}