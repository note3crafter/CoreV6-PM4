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
use TheNote\core\formapi\SimpleForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class StatsCommand extends Command {
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("stats", $config->get("prefix") . "§6Schaue deine Stats an", "/stats");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) :bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $stats = new Config($this->plugin->getDataFolder() . Main::$statsfile . $sender->getName() . ".json", Config::JSON);

        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }

		/*$kills = $this->getPlayerKillPoints($player);
		$deaths = $this->getPlayerDeathPoints($player);
		if($deaths !== 0){
			$ratio = $kills / $deaths;
			if($ratio !== 0){
				return number_format($ratio, 1);
			}
		}*/
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
        $form->setContent("§6======§f[§eStatistiken§f]§6======\n" .
            "§eDeine Statistiken\n" .
            "Deine Joins : " . $stats->get("joins") . "\n" .
            "Deine Sprünge : " . $stats->get("jumps") . "\n" .
            "Deine Kicks : " . $stats->get("kicks") . "\n" .
            "Deine Interacts : " . $stats->get("interact") . "\n" .
            "Gelaufene Meter : " . round($stats->get("movewalk")) . "m\n" .
            "Geflogene Meter : " . round($stats->get("movefly")) . "m\n" .
            "Blöcke abgebaut : " . $stats->get("break") . "\n" .
            "Blöcke gesetzt : " . $stats->get("place") . "\n" .
            "Gedroppte Items : " . $stats->get("drop") . "\n" .
            "Gesammelte Items : " . $stats->get("pick") . "\n" .
            "Consumierte Items : " . $stats->get("consume") . "\n" .
            "Deine Nachrrichten : " . $stats->get("messages") . "\n".
            "Deine Votes : " . $stats->get("votes"));

        $form->addButton("§0OK", 0);
        $form->sendToPlayer($sender);
        return true;
    }
}