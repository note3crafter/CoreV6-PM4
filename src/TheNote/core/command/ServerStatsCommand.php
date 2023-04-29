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
use TheNote\core\BaseAPI;
use TheNote\core\Main;
use TheNote\core\formapi\SimpleForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class ServerStatsCommand extends Command
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("serverstats", $api->getSetting("prefix") . "§6Schaue die Serverstatistiken an", "/serverstats", ["sstats"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }
        $stats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
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
        $form->setContent("§0======§f[§dStatistiken§f]§0======\n" .
            "§eGesammte Joins : \n" .
            "§d" . $stats->get("joins") . "\n" .
            "§eGesammte Jumps : \n" .
            "§d" . $stats->get("jumps") . "\n" .
            "§eGesammte Kicks : \n" .
            "§d" . $stats->get("kicks") . "\n" .
            "§eGesammte Deaths : \n" .
            "§d" . $stats->get("deaths") . "\n" .
            "§eGesammte Blöcke abgebaut : \n" .
            "§d" . $stats->get("break") . "\n" .
            "§eBlöcke insgesammt gesetzt : \n" .
            "§d" . $stats->get("place") . "\n" .
            "§eGesammt gelaufene Meter : \n" .
            "§d" . round($stats->get("movewalk")) . "m\n" .
            "§eGesammt geflogene Meter : \n" .
            "§d" . round($stats->get("movefly")) . "m\n" .
            "§eGedroppte Items : \n" .
            "§d" . $stats->get("drop") . "\n" .
            "§eGesammelte Items : \n" .
            "§d" . $stats->get("pick") . "\n" .
            "§eConsumierte Items : \n" .
            "§d" . $stats->get("consume") . "\n" .
            "§eInsgesammt gesendete Nachrrichten : \n" .
            "§d" . $stats->get("messages") . "\n" .
            "§eInsgesammte Neustarts : \n" .
            "§d" . $stats->get("restarts") . "\n" .
            "§eRegestrierte Spieler : \n" .
            "§d" . $stats->get("Users") . "\n" .
            "§eInsgesammte Votes : \n" .
            "§d" . $stats->get("votes"));

        $form->addButton("§0OK", 0);
        $form->sendToPlayer($sender);
        return true;
    }
}