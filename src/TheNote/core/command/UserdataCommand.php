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
use TheNote\core\formapi\SimpleForm;

class UserdataCommand extends Command
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("userdata", $config->get("prefix") . $lang->get("userdataprefix") , "/userdata", ["user", "ud"]);
        $this->setPermission("core.command.userdata");
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
        if (!isset($args[0])) {
            $sender->sendMessage($config->get("error") . $lang->get("userdatanoplayer"));
            return false;
        }
        if (!file_exists($this->plugin->getDataFolder() . Main::$logdatafile . "$args[0].json")) {
            $sender->sendMessage($config->get("error") . $lang->get("userdataerror"));
            return false;
        }
        if (isset($args[0])) {
            $ud = new Config($this->plugin->getDataFolder() . Main::$logdatafile . "$args[0].json", Config::JSON);
            if ($args[0]) {
                if (!file_exists($this->plugin->getDataFolder() . Main::$logdatafile . "$args[0].json")) {
                    $sender->sendMessage($config->get("error") . $lang->get("userdataerror"));
                    return true;
                } else {
                    $form = new SimpleForm(function (Player $sender, $data) {
                        $result = $data;
                        if ($result === null) {
                            return true;
                        }
                        switch ($result) {
                            case 0:
                                break;
                        }
                    });
                    $form->setTitle($config->get("uiname"));
                    $form->setContent("§6Spielerdaten vom Spieler $args[0];\n" .
                        "Spielername : " . $ud->get("Name") . "\n" .
                        "Erste IP-Adresse : " . $ud->get("first-ip") . "\n" .
                        "Erste Xbox-ID : " . $ud->get("first-XboxID") . "\n" .
                        "Erstes OS : " . $ud->get("first_OS") . "\n" .
                        "Erste UUID : " . $ud->get("first-uuid") . "\n" .
                        "Erster Join : " . $ud->get("first-join") . "\n" .
                        "IP-Adresse : " . $ud->get("IP") . "\n" .
                        "Xbox-ID : " . $ud->get("Xbox-ID") . "\n" .
                        "OS : " . $ud->get("OS") . "\n" .
                        "UUID : " . $ud->get("ID") . "\n" .
                        "Letzter Join : " . $ud->get("Last_Join"));

                    $form->addButton("§0OK", 0);
                    $form->sendToPlayer($sender);
                }
            }
        } else {
            $sender->sendMessage($config->get("error") . $lang->get("userdatanoplayer"));
        }
        return true;
    }
}