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
use TheNote\core\BaseAPI;
use TheNote\core\Main;
use TheNote\core\formapi\SimpleForm;

class UserdataCommand extends Command
{
    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("userdata", $api->getSetting("prefix") . $api->getLang("userdataprefix") , "/userdata", ["user", "ud"]);
        $this->setPermission("core.command.userdata");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("commandingame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("nopermission"));
            return false;
        }
        $target = $api->findPlayer($sender, $args[0]);
        if (!file_exists($this->plugin->getDataFolder() . Main::$logdatafile . strtolower($target) . ".json")) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("userdataerror"));
            return false;
        }
        if (isset($args[0])) {
            $ud = new Config($this->plugin->getDataFolder() . Main::$logdatafile . strtolower($target) . ".json", Config::JSON);
            if ($args[0]) {
                if (!file_exists($this->plugin->getDataFolder() . Main::$logdatafile . strtolower($target) . ".json")) {
                    $sender->sendMessage($api->getSetting("error") . $api->getLang("userdataerror"));
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
                    $form->setTitle($api->getSetting("uiname"));
                    $form->setContent("§6Spielerdaten vom Spieler $target;\n" .
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
            $sender->sendMessage($api->getSetting("error") . $api->getLang("userdatanoplayer"));
        }
        return true;
    }
}