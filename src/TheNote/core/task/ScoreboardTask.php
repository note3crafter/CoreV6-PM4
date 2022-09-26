<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\Task;

use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\Main;
use TheNote\core\utils\Manager as SBM;

class ScoreboardTask extends Task
{
    //private $plugin;
    //private $line = [];

    function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    function numberPacket(Player $player, $score = 1, $msg = ""): void
    {
        $entrie = new ScorePacketEntry();
        $entrie->objectiveName = "test";
        $entrie->type = 3;
        $entrie->customName = str_repeat("", 5) . $msg . str_repeat(" ", 1);
        $entrie->score = $score;
        $entrie->scoreboardId = $score;
        $pk = new SetScorePacket();
        $pk->type = 1;
        $pk->entries[] = $entrie;
        $player->getNetworkSession()->sendDataPacket($pk);
        $pk2 = new SetScorePacket();
        $pk2->entries[] = $entrie;
        $pk2->type = 0;
        $player->getNetworkSession()->sendDataPacket($pk2);
    }

    public function onRun(): void
    {
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
            $user = new Config($this->plugin->getDataFolder() . Main::$userfile . $player->getName() . ".json", Config::JSON);
            $gruppe = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $player->getName() . ".json", Config::JSON);
            $online = new Config($this->plugin->getDataFolder() . Main::$cloud . "Count.json", Config::JSON);
            $stats = new Config($this->plugin->getDataFolder() . Main::$statsfile . $player->getName() . ".json", Config::JSON);
            $hei = new Config($this->plugin->getDataFolder() . Main::$heifile . $player->getName() . ".json", Config::JSON);
            $settings = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
            $playerdata = new Config($this->plugin->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
            $money = new Config($this->plugin->getDataFolder() . Main::$cloud . "Money.yml", Config::YAML);
            $sbconfig = new Config($this->plugin->getDataFolder() . Main::$setup . "Scoreboard.yml", Config::YAML);

            $pk = new SetDisplayObjectivePacket();
            $pk->displaySlot = "sidebar";
            $pk->objectiveName = "test";
            $pk->displayName = $sbconfig->get("title");
            $pk->criteriaName = "dummy";
            $pk->sortOrder = 0;
            $player->getNetworkSession()->sendDataPacket($pk);

            if($sbconfig->get("l1") === true) {
                $cfg1 = SBM::formateString($this->plugin, $player, $sbconfig->get("line1"));
                $this->numberPacket($player, 1, $cfg1);
            }
            if ($sbconfig->get("l2") === true) {
                $cfg2 = SBM::formateString($this->plugin, $player, $sbconfig->get("line2"));
                $this->numberPacket($player, 2, $cfg2);
            }
            if ($sbconfig->get("l3") === true) {
                $cfg3 = SBM::formateString($this->plugin, $player, $sbconfig->get("line3"));
                $this->numberPacket($player, 3, $cfg3);
            }
            if ($sbconfig->get("l4") === true) {
                $cfg4 = SBM::formateString($this->plugin, $player, $sbconfig->get("line4"));
                $this->numberPacket($player, 4, $cfg4);
            }
            if ($sbconfig->get("l5") === true) {
                $cfg5 = SBM::formateString($this->plugin, $player, $sbconfig->get("line5"));
                $this->numberPacket($player, 5, $cfg5);
            }
            if ($sbconfig->get("l6") === true) {
                $cfg6 = SBM::formateString($this->plugin, $player, $sbconfig->get("line6"));
                $this->numberPacket($player, 6, $cfg6);
            }
            if ($sbconfig->get("l7") === true) {
                $cfg7 = SBM::formateString($this->plugin, $player, $sbconfig->get("line7"));
                $this->numberPacket($player, 7, $cfg7);
            }
            if ($sbconfig->get("l8") === true) {
                $cfg8 = SBM::formateString($this->plugin, $player, $sbconfig->get("line8"));
                $this->numberPacket($player, 8, $cfg8);
            }
            if ($sbconfig->get("l9") === true) {
                $cfg9 = SBM::formateString($this->plugin, $player, $sbconfig->get("line9"));
                $this->numberPacket($player, 9, $cfg9);
            }
            if ($sbconfig->get("l10") === true) {
                $cfg10 = SBM::formateString($this->plugin, $player, $sbconfig->get("line10"));
                $this->numberPacket($player, 10, $cfg10);
            }
            if ($sbconfig->get("l11") === true) {
                $cfg11 = SBM::formateString($this->plugin, $player, $sbconfig->get("line11"));
                $this->numberPacket($player, 11, $cfg11);
            }
            if ($sbconfig->get("l12") === true) {
                $cfg12 = SBM::formateString($this->plugin, $player, $sbconfig->get("line12"));
                $this->numberPacket($player, 12, $cfg12);
            }
            if ($sbconfig->get("l13") === true) {
                $cfg13 = SBM::formateString($this->plugin, $player, $sbconfig->get("line13"));
                $this->numberPacket($player, 13, $cfg13);
            }
            if ($sbconfig->get("l14") === true) {
                $cfg14 = SBM::formateString($this->plugin, $player, $sbconfig->get("line14"));
                $this->numberPacket($player, 14, $cfg14);
            }
            if ($sbconfig->get("l15") === true) {
                $cfg15 = SBM::formateString($this->plugin, $player, $sbconfig->get("line15"));
                $this->numberPacket($player, 15, $cfg15);
            }

            $mymoney = $money->getNested("money." . $player->getName());
            $votes = $stats->get("votes");
            $joins = $stats->get("joins");
            $os = $this->plugin->getPlayerPlatform($player);
            $player->setScoreTag("§eVotes §f: §6$votes\n §eJoins §f: §6$joins\n§6$os");
        }
    }
}