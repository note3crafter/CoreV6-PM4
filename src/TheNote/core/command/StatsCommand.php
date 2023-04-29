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

class StatsCommand extends Command {
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("stats", $api->getSetting("prefix") . "§6Schaue deine Stats an", "/stats");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) :bool
    {
        $api = new BaseAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . "§cDiesen Command kannst du nur Ingame benutzen");
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
        $form->setTitle($api->getSetting("uiname"));
        $form->setContent("§6======§f[§eStatistiken§f]§6======\n" .
            "§eDeine Statistiken\n" .
            "Deine Joins : " . $api->getJoinPoints($sender->getName()) . "\n" .
            "Deine Sprünge : " . $api->getJumpPoints($sender->getName()) . "\n" .
            "Deine Kicks : " . $api->getKickPoints($sender->getName()) . "\n" .
            "Deine Interacts : " . $api->getInteractPoints($sender->getName()) . "\n" .
            "Gelaufene Meter : " . round($api->getWalkPoints($sender->getName())) . "m\n" .
            "Geflogene Meter : " . round($api->getFlyPoints($sender->getName())) . "m\n" .
            "Blöcke abgebaut : " . $api->getBreakPoints($sender->getName()) . "\n" .
            "Blöcke gesetzt : " . $api->getPlacePoints($sender->getName()) . "\n" .
            "Gedroppte Items : " . $api->getDropPoints($sender->getName()) . "\n" .
            "Gesammelte Items : " . $api->getPickPoints($sender->getName()) . "\n" .
            "Consumierte Items : " . $api->getConsumePoints($sender->getName()) . "\n" .
            "Deine Nachrrichten : " . $api->getMessagePoints($sender->getName()) . "\n".
            "Deine Votes : " . $api->getVotePoints($sender->getName()));

        $form->addButton("§0OK", 0);
        $form->sendToPlayer($sender);
        return true;
    }
}