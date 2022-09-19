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

use onebone\economyapi\EconomyAPI;
use cooldogedev\BedrockEconomy\api\BedrockEconomyAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use TheNote\core\Main;

class PayallCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("payall", $config->get("prefix") . $lang->get("payallprefix"), "/payall", ["paya"]);
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
        if (isset($args[0])) {
            if (is_numeric($args[0])) {
                $amount = $args[0];
                $anz = count($this->plugin->getServer()->getOnlinePlayers());
                $tanz = $anz - 1;
                $maxpay = $amount * $tanz;
                if ($this->plugin->economyapi == null){
                    $mymoney = $money->getNested("money." . $sender->getName());
                } else {
                    $mymoney = EconomyAPI::getInstance()->myMoney($sender);
                }
                if ($maxpay <= $mymoney) {
                    foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
                        $name = $player->getName();
                        $iname = strtolower($name);
                        if ($this->plugin->economyapi == null /*and $this->plugin->bedrockeconomy == null*/) {
                            $money->setNested("money." . $iname, $money->getNested("money." . $iname) + $amount);
                            $money->setNested("money." . $sender->getName(), $money->getNested("money." . $sender->getName()) - $amount);
                            $money->save();
                        } else/*if ($this->plugin->bedrockeconomy == null)*/ {
                            EconomyAPI::getInstance()->addMoney($iname, $amount);
                            EconomyAPI::getInstance()->reduceMoney($sender, $amount);
                        }/* elseif ($this->plugin->economyapi == null) {
                            BedrockEconomyAPI::legacy()->transferFromPlayerBalance($sender, $iname, $amount);
                        }*/
                    }
                    $message = str_replace("{name}", $sender->getNameTag(), $lang->get("payallbc"));
                    $message1 = str_replace("{money}", $args[0] , $message);
                    $message2 =str_replace("{amount}", $amount, $message1);
                    $this->plugin->getServer()->broadcastMessage($config->get("prefix") . $message2);
                } else {
                    $sender->sendMessage($config->get("error") . $lang->get("payallnomoney"));
                }
            } else {
                $sender->sendMessage($config->get("error") . $lang->get("payallwrong"));
            }
        } else {
            $sender->sendMessage($config->get("error") . $lang->get("payallnovalue"));
        }
        return true;
    }
}
