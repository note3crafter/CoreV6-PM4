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
use TheNote\core\utils\ScoreboardManager as SBM;

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
                $cfg1 = SBM::formateString($player, $sbconfig->get("line1"));
                $this->numberPacket($player, 1, $cfg1);
            }
            if ($sbconfig->get("l2") === true) {
                $cfg2 = SBM::formateString($player, $sbconfig->get("line2"));
                $this->numberPacket($player, 2, $cfg2);
            }
            if ($sbconfig->get("l3") === true) {
                $cfg3 = SBM::formateString($player, $sbconfig->get("line3"));
                $this->numberPacket($player, 3, $cfg3);
            }
            if ($sbconfig->get("l4") === true) {
                $cfg4 = SBM::formateString($player, $sbconfig->get("line4"));
                $this->numberPacket($player, 4, $cfg4);
            }
            if ($sbconfig->get("l5") === true) {
                $cfg5 = SBM::formateString($player, $sbconfig->get("line5"));
                $this->numberPacket($player, 5, $cfg5);
            }
            if ($sbconfig->get("l6") === true) {
                $cfg6 = SBM::formateString($player, $sbconfig->get("line6"));
                $this->numberPacket($player, 6, $cfg6);
            }
            if ($sbconfig->get("l7") === true) {
                $cfg7 = SBM::formateString($player, $sbconfig->get("line7"));
                $this->numberPacket($player, 7, $cfg7);
            }
            if ($sbconfig->get("l8") === true) {
                $cfg8 = SBM::formateString($player, $sbconfig->get("line8"));
                $this->numberPacket($player, 8, $cfg8);
            }
            if ($sbconfig->get("l9") === true) {
                $cfg9 = SBM::formateString($player, $sbconfig->get("line9"));
                $this->numberPacket($player, 9, $cfg9);
            }
            if ($sbconfig->get("l10") === true) {
                $cfg10 = SBM::formateString($player, $sbconfig->get("line10"));
                $this->numberPacket($player, 10, $cfg10);
            }
            if ($sbconfig->get("l11") === true) {
                $cfg11 = SBM::formateString($player, $sbconfig->get("line11"));
                $this->numberPacket($player, 11, $cfg11);
            }
            if ($sbconfig->get("l12") === true) {
                $cfg12 = SBM::formateString($player, $sbconfig->get("line12"));
                $this->numberPacket($player, 12, $cfg12);
            }
            if ($sbconfig->get("l13") === true) {
                $cfg13 = SBM::formateString($player, $sbconfig->get("line13"));
                $this->numberPacket($player, 13, $cfg13);
            }
            if ($sbconfig->get("l14") === true) {
                $cfg14 = SBM::formateString($player, $sbconfig->get("line14"));
                $this->numberPacket($player, 14, $cfg14);
            }
            if ($sbconfig->get("l15") === true) {
                $cfg15 = SBM::formateString($player, $sbconfig->get("line15"));
                $this->numberPacket($player, 15, $cfg15);
            }



            $mymoney = $money->getNested("money." . $player->getName());
            $votes = $stats->get("votes");
            $joins = $stats->get("joins");
            $os = $this->plugin->getPlayerPlatform($player);
            $player->setScoreTag("§eVotes §f: §6$votes\n §eJoins §f: §6$joins\n§6$os");



           /* $this->numberPacket($player, 1, "§eDein Rang");
            $this->numberPacket($player, 2, "§f➥ " . $playerdata->getNested($player->getName() . ".groupprefix"));
            $this->numberPacket($player, 3, "§eDein Geldstand");
            if ($this->plugin->economyapi === null) {
                $this->numberPacket($player, 4, "§f➥ §e" . $mymoney . "§e$");
            } else {
                $this->numberPacket($player, 4, "§f➥ §e" . $this->plugin->economyapi->myMoney($player) . "§e$");
            }
            $this->numberPacket($player, 5, "§eDeine Coins");
            $this->numberPacket($player, 6, "§f➥ §e" . $user->get("coins"));
            $this->numberPacket($player, 7, "§aDein Partner§f/§ain");
            if ($user->get("heistatus") === false) {
                $this->numberPacket($player, 8, "§f➥ §aKein Partner");
            } else {
                $this->numberPacket($player, 8, "§f➥ §a" . $hei->get("heiraten"));
            }
            $this->numberPacket($player, 9, "§dDein Clan");
            if ($gruppe->get("ClanStatus") === false) {
                $this->numberPacket($player, 10, "§f➥ §dKein Clan");
            } else {
                $this->numberPacket($player, 10, "§f➥ §d" . $gruppe->get("Clan"));
            }
            $this->numberPacket($player, 11, "§eOnline");
            $this->numberPacket($player, 12, "§f➥ §e" . $online->get("players") . "§f/§e" . $settings->get("slots") . "§f");*/

            //ScoreboardConfig zukünftig

            //Line1
            /*$slots1 = str_replace("{slots}", $settings->get("slots"), $sbconfig->get("Line1"));
            $oline1 = str_replace("{online}", $online->get("players"), $slots1);
            if ($gruppe->get("ClanStatus") === false) {
                $clan1 = str_replace("{clan}", "Kein Clan", $oline1);
            } else {
                $clan1 = str_replace("{clan}", $gruppe->get("Clan"), $oline1);
            }
            if ($user->get("heistatus") === false) {
                $marry1 = str_replace("{marry}", "Kein Partner", $clan1);
            } else {
                $marry1 = str_replace("{marry}", $hei->get("heiraten"), $clan1);
            }
            $coins1 = str_replace("{coins}", $user->get("coins"), $marry1);
            $rank1 = str_replace("{rank}", $playerdata->getNested($player->getName() . ".groupprefix"), $coins1);
            $username1 = str_replace("{username}", $player->getName(), $rank1);
            if ($this->plugin->economyapi === null) {
                $money1 = str_replace("{money}", $mymoney, $username1);
            } else {
                $money1 = str_replace("{money}", $this->plugin->economyapi->myMoney($player), $username1);
            }

            //Line2
            $slots2 = str_replace("{slots}", $settings->get("slots"), $sbconfig->get("Line2"));
            $oline2 = str_replace("{online}", $online->get("players"), $slots2);
            if ($gruppe->get("ClanStatus") === false) {
                $clan2 = str_replace("{clan}", "Kein Clan", $oline2);
            } else {
                $clan2 = str_replace("{clan}", $gruppe->get("Clan"), $oline2);
            }
            if ($user->get("heistatus") === false) {
                $marry2 = str_replace("{marry}", "Kein Partner", $clan2);
            } else {
                $marry2 = str_replace("{marry}", $hei->get("heiraten"), $clan2);
            }
            $coins2 = str_replace("{coins}", $user->get("coins"), $marry2);
            $rank2 = str_replace("{rank}", $playerdata->getNested($player->getName() . ".groupprefix"), $coins2);
            $username2 = str_replace("{username}", $player->getName(), $rank2);
            if ($this->plugin->economyapi === null) {
                $money2 = str_replace("{money}", $mymoney, $username2);
            } else {
                $money2 = str_replace("{money}", $this->plugin->economyapi->myMoney($player), $username2);
            }

            //Line3
            $slots3 = str_replace("{slots}", $settings->get("slots"), $sbconfig->get("Line3"));
            $oline3 = str_replace("{online}", $online->get("players"), $slots3);
            if ($gruppe->get("ClanStatus") === false) {
                $clan3 = str_replace("{clan}", "Kein Clan", $oline3);
            } else {
                $clan3 = str_replace("{clan}", $gruppe->get("Clan"), $oline3);
            }
            if ($user->get("heistatus") === false) {
                $marry3 = str_replace("{marry}", "Kein Partner", $clan3);
            } else {
                $marry3 = str_replace("{marry}", $hei->get("heiraten"), $clan3);
            }
            $coins3 = str_replace("{coins}", $user->get("coins"), $marry3);
            $rank3 = str_replace("{rank}", $playerdata->getNested($player->getName() . ".groupprefix"), $coins3);
            $username3 = str_replace("{username}", $player->getName(), $rank3);
            if ($this->plugin->economyapi === null) {
                $money3 = str_replace("{money}", $mymoney, $username1);
            } else {
                $money3 = str_replace("{money}", $this->plugin->economyapi->myMoney($player), $username3);
            }

            //Line4
            $slots4 = str_replace("{slots}", $settings->get("slots"), $sbconfig->get("Line4"));
            $oline4 = str_replace("{online}", $online->get("players"), $slots4);
            if ($gruppe->get("ClanStatus") === false) {
                $clan4 = str_replace("{clan}", "Kein Clan", $oline4);
            } else {
                $clan4 = str_replace("{clan}", $gruppe->get("Clan"), $oline4);
            }
            if ($user->get("heistatus") === false) {
                $marry4 = str_replace("{marry}", "Kein Partner", $clan4);
            } else {
                $marry4 = str_replace("{marry}", $hei->get("heiraten"), $clan4);
            }
            $coins4 = str_replace("{coins}", $user->get("coins"), $marry4);
            $rank4 = str_replace("{rank}", $playerdata->getNested($player->getName() . ".groupprefix"), $coins4);
            $username4 = str_replace("{username}", $player->getName(), $rank4);
            if ($this->plugin->economyapi === null) {
                $money4 = str_replace("{money}", $mymoney, $username4);
            } else {
                $money4 = str_replace("{money}", $this->plugin->economyapi->myMoney($player), $username4);
            }

            //Line5
            $slots5 = str_replace("{slots}", $settings->get("slots"), $sbconfig->get("Line5"));
            $oline5 = str_replace("{online}", $online->get("players"), $slots5);
            if ($gruppe->get("ClanStatus") === false) {
                $clan5 = str_replace("{clan}", "Kein Clan", $oline5);
            } else {
                $clan5 = str_replace("{clan}", $gruppe->get("Clan"), $oline5);
            }
            if ($user->get("heistatus") === false) {
                $marry5 = str_replace("{marry}", "Kein Partner", $clan5);
            } else {
                $marry5 = str_replace("{marry}", $hei->get("heiraten"), $clan5);
            }
            $coins5 = str_replace("{coins}", $user->get("coins"), $marry5);
            $rank5 = str_replace("{rank}", $playerdata->getNested($player->getName() . ".groupprefix"), $coins5);
            $username5 = str_replace("{username}", $player->getName(), $rank5);
            if ($this->plugin->economyapi === null) {
                $money5 = str_replace("{money}", $mymoney, $username5);
            } else {
                $money5 = str_replace("{money}", $this->plugin->economyapi->myMoney($player), $username5);
            }

            //Line6
            $slots6 = str_replace("{slots}", $settings->get("slots"), $sbconfig->get("Line6"));
            $oline6 = str_replace("{online}", $online->get("players"), $slots6);
            if ($gruppe->get("ClanStatus") === false) {
                $clan6 = str_replace("{clan}", "Kein Clan", $oline6);
            } else {
                $clan6 = str_replace("{clan}", $gruppe->get("Clan"), $oline6);
            }
            if ($user->get("heistatus") === false) {
                $marry6 = str_replace("{marry}", "Kein Partner", $clan6);
            } else {
                $marry6 = str_replace("{marry}", $hei->get("heiraten"), $clan6);
            }
            $coins6 = str_replace("{coins}", $user->get("coins"), $marry6);
            $rank6 = str_replace("{rank}", $playerdata->getNested($player->getName() . ".groupprefix"), $coins6);
            $username6 = str_replace("{username}", $player->getName(), $rank6);
            if ($this->plugin->economyapi === null) {
                $money6 = str_replace("{money}", $mymoney, $username6);
            } else {
                $money6 = str_replace("{money}", $this->plugin->economyapi->myMoney($player), $username6);
            }

            //Line7
            $slots7 = str_replace("{slots}", $settings->get("slots"), $sbconfig->get("Line7"));
            $oline7 = str_replace("{online}", $online->get("players"), $slots7);
            if ($gruppe->get("ClanStatus") === false) {
                $clan7 = str_replace("{clan}", "Kein Clan", $oline7);
            } else {
                $clan7 = str_replace("{clan}", $gruppe->get("Clan"), $oline7);
            }
            if ($user->get("heistatus") === false) {
                $marry7 = str_replace("{marry}", "Kein Partner", $clan7);
            } else {
                $marry7 = str_replace("{marry}", $hei->get("heiraten"), $clan7);
            }
            $coins7 = str_replace("{coins}", $user->get("coins"), $marry7);
            $rank7 = str_replace("{rank}", $playerdata->getNested($player->getName() . ".groupprefix"), $coins7);
            $username7 = str_replace("{username}", $player->getName(), $rank7);
            if ($this->plugin->economyapi === null) {
                $money7 = str_replace("{money}", $mymoney, $username7);
            } else {
                $money7 = str_replace("{money}", $this->plugin->economyapi->myMoney($player), $username7);
            }

            //Line8
            $slots8 = str_replace("{slots}", $settings->get("slots"), $sbconfig->get("Line8"));
            $oline8 = str_replace("{online}", $online->get("players"), $slots8);
            if ($gruppe->get("ClanStatus") === false) {
                $clan8 = str_replace("{clan}", "Kein Clan", $oline8);
            } else {
                $clan8 = str_replace("{clan}", $gruppe->get("Clan"), $oline8);
            }
            if ($user->get("heistatus") === false) {
                $marry8 = str_replace("{marry}", "Kein Partner", $clan8);
            } else {
                $marry8 = str_replace("{marry}", $hei->get("heiraten"), $clan8);
            }
            $coins8 = str_replace("{coins}", $user->get("coins"), $marry8);
            $rank8 = str_replace("{rank}", $playerdata->getNested($player->getName() . ".groupprefix"), $coins8);
            $username8 = str_replace("{username}", $player->getName(), $rank8);
            if ($this->plugin->economyapi === null) {
                $money8 = str_replace("{money}", $mymoney, $username8);
            } else {
                $money8 = str_replace("{money}", $this->plugin->economyapi->myMoney($player), $username8);
            }

            //Line9
            $slots9 = str_replace("{slots}", $settings->get("slots"), $sbconfig->get("Line9"));
            $oline9 = str_replace("{online}", $online->get("players"), $slots9);
            if ($gruppe->get("ClanStatus") === false) {
                $clan9 = str_replace("{clan}", "Kein Clan", $oline9);
            } else {
                $clan9 = str_replace("{clan}", $gruppe->get("Clan"), $oline9);
            }
            if ($user->get("heistatus") === false) {
                $marry9 = str_replace("{marry}", "Kein Partner", $clan9);
            } else {
                $marry9 = str_replace("{marry}", $hei->get("heiraten"), $clan9);
            }
            $coins9 = str_replace("{coins}", $user->get("coins"), $marry9);
            $rank9 = str_replace("{rank}", $playerdata->getNested($player->getName() . ".groupprefix"), $coins9);
            $username9 = str_replace("{username}", $player->getName(), $rank9);
            if ($this->plugin->economyapi === null) {
                $money9 = str_replace("{money}", $mymoney, $username1);
            } else {
                $money9 = str_replace("{money}", $this->plugin->economyapi->myMoney($player), $username9);
            }

            //Line10
            $slots10 = str_replace("{slots}", $settings->get("slots"), $sbconfig->get("Line10"));
            $oline10 = str_replace("{online}", $online->get("players"), $slots10);
            if ($gruppe->get("ClanStatus") === false) {
                $clan10 = str_replace("{clan}", "Kein Clan", $oline10);
            } else {
                $clan10 = str_replace("{clan}", $gruppe->get("Clan"), $oline10);
            }
            if ($user->get("heistatus") === false) {
                $marry10 = str_replace("{marry}", "Kein Partner", $clan10);
            } else {
                $marry10 = str_replace("{marry}", $hei->get("heiraten"), $clan10);
            }
            $coins10 = str_replace("{coins}", $user->get("coins"), $marry10);
            $rank10 = str_replace("{rank}", $playerdata->getNested($player->getName() . ".groupprefix"), $coins10);
            $username10 = str_replace("{username}", $player->getName(), $rank10);
            if ($this->plugin->economyapi === null) {
                $money10 = str_replace("{money}", $mymoney, $username10);
            } else {
                $money10 = str_replace("{money}", $this->plugin->economyapi->myMoney($player), $username10);
            }

            //Line11
            $slots11 = str_replace("{slots}", $settings->get("slots"), $sbconfig->get("Line11"));
            $oline11 = str_replace("{online}", $online->get("players"), $slots11);
            if ($gruppe->get("ClanStatus") === false) {
                $clan11 = str_replace("{clan}", "Kein Clan", $oline11);
            } else {
                $clan11 = str_replace("{clan}", $gruppe->get("Clan"), $oline11);
            }
            if ($user->get("heistatus") === false) {
                $marry11 = str_replace("{marry}", "Kein Partner", $clan11);
            } else {
                $marry11 = str_replace("{marry}", $hei->get("heiraten"), $clan11);
            }
            $coins11 = str_replace("{coins}", $user->get("coins"), $marry11);
            $rank11 = str_replace("{rank}", $playerdata->getNested($player->getName() . ".groupprefix"), $coins11);
            $username11 = str_replace("{username}", $player->getName(), $rank11);
            if ($this->plugin->economyapi === null) {
                $money11 = str_replace("{money}", $mymoney, $username11);
            } else {
                $money11 = str_replace("{money}", $this->plugin->economyapi->myMoney($player), $username11);
            }

            //Line12
            $slots12 = str_replace("{slots}", $settings->get("slots"), $sbconfig->get("Line12"));
            $oline12 = str_replace("{online}", $online->get("players"), $slots12);
            if ($gruppe->get("ClanStatus") === false) {
                $clan12 = str_replace("{clan}", "Kein Clan", $oline12);
            } else {
                $clan12 = str_replace("{clan}", $gruppe->get("Clan"), $oline12);
            }
            if ($user->get("heistatus") === false) {
                $marry12 = str_replace("{marry}", "Kein Partner", $clan12);
            } else {
                $marry12 = str_replace("{marry}", $hei->get("heiraten"), $clan12);
            }
            $coins12 = str_replace("{coins}", $user->get("coins"), $marry12);
            $rank12 = str_replace("{rank}", $playerdata->getNested($player->getName() . ".groupprefix"), $coins12);
            $username12 = str_replace("{username}", $player->getName(), $rank12);
            if ($this->plugin->economyapi === null) {
                $money12 = str_replace("{money}", $mymoney, $username12);
            } else {
                $money12 = str_replace("{money}", $this->plugin->economyapi->myMoney($player), $username12);
            }

            //Line13
            $slots13 = str_replace("{slots}", $settings->get("slots"), $sbconfig->get("Line13"));
            $oline13 = str_replace("{online}", $online->get("players"), $slots13);
            if ($gruppe->get("ClanStatus") === false) {
                $clan13 = str_replace("{clan}", "Kein Clan", $oline13);
            } else {
                $clan13 = str_replace("{clan}", $gruppe->get("Clan"), $oline13);
            }
            if ($user->get("heistatus") === false) {
                $marry13 = str_replace("{marry}", "Kein Partner", $clan13);
            } else {
                $marry13 = str_replace("{marry}", $hei->get("heiraten"), $clan13);
            }
            $coins13 = str_replace("{coins}", $user->get("coins"), $marry13);
            $rank13 = str_replace("{rank}", $playerdata->getNested($player->getName() . ".groupprefix"), $coins13);
            $username13 = str_replace("{username}", $player->getName(), $rank13);
            if ($this->plugin->economyapi === null) {
                $money13 = str_replace("{money}", $mymoney, $username13);
            } else {
                $money13 = str_replace("{money}", $this->plugin->economyapi->myMoney($player), $username13);
            }

            //Line14
            $slots14 = str_replace("{slots}", $settings->get("slots"), $sbconfig->get("Line14"));
            $oline14 = str_replace("{online}", $online->get("players"), $slots14);
            if ($gruppe->get("ClanStatus") === false) {
                $clan14 = str_replace("{clan}", "Kein Clan", $oline14);
            } else {
                $clan14 = str_replace("{clan}", $gruppe->get("Clan"), $oline14);
            }
            if ($user->get("heistatus") === false) {
                $marry14 = str_replace("{marry}", "Kein Partner", $clan14);
            } else {
                $marry14 = str_replace("{marry}", $hei->get("heiraten"), $clan14);
            }
            $coins14 = str_replace("{coins}", $user->get("coins"), $marry14);
            $rank14 = str_replace("{rank}", $playerdata->getNested($player->getName() . ".groupprefix"), $coins14);
            $username14 = str_replace("{username}", $player->getName(), $rank14);
            if ($this->plugin->economyapi === null) {
                $money14 = str_replace("{money}", $mymoney, $username14);
            } else {
                $money14 = str_replace("{money}", $this->plugin->economyapi->myMoney($player), $username14);
            }

            //Line15
            $slots15 = str_replace("{slots}", $settings->get("slots"), $sbconfig->get("Line15"));
            $oline15 = str_replace("{online}", $online->get("players"), $slots15);
            if ($gruppe->get("ClanStatus") === false) {
                $clan15 = str_replace("{clan}", "Kein Clan", $oline15);
            } else {
                $clan15 = str_replace("{clan}", $gruppe->get("Clan"), $oline15);
            }
            if ($user->get("heistatus") === false) {
                $marry15 = str_replace("{marry}", "Kein Partner", $clan15);
            } else {
                $marry15 = str_replace("{marry}", $hei->get("heiraten"), $clan15);
            }
            $coins15 = str_replace("{coins}", $user->get("coins"), $marry15);
            $rank15 = str_replace("{rank}", $playerdata->getNested($player->getName() . ".groupprefix"), $coins15);
            $username15 = str_replace("{username}", $player->getName(), $rank15);
            if ($this->plugin->economyapi === null) {
                $money15 = str_replace("{money}", $mymoney, $username15);
            } else {
                $money15 = str_replace("{money}", $this->plugin->economyapi->myMoney($player), $username15);
            }

            if ($sbconfig->get("lines") === 1) {
                $this->numberPacket($player, 1, $money1);
            } elseif ($sbconfig->get("lines") === 2) {
                $this->numberPacket($player, 1, $money1);
                $this->numberPacket($player, 2, $money2);
            } elseif ($sbconfig->get("lines") === 3) {
                $this->numberPacket($player, 1, $money1);
                $this->numberPacket($player, 2, $money2);
                $this->numberPacket($player, 3, $money3);
            } elseif ($sbconfig->get("lines") === 4) {
                $this->numberPacket($player, 1, $money1);
                $this->numberPacket($player, 2, $money2);
                $this->numberPacket($player, 3, $money3);
                $this->numberPacket($player, 4, $money4);
            } elseif ($sbconfig->get("lines") === 5) {
                $this->numberPacket($player, 1, $money1);
                $this->numberPacket($player, 2, $money2);
                $this->numberPacket($player, 3, $money3);
                $this->numberPacket($player, 4, $money4);
                $this->numberPacket($player, 5, $money5);
            } elseif ($sbconfig->get("lines") === 6) {
                $this->numberPacket($player, 1, $money1);
                $this->numberPacket($player, 2, $money2);
                $this->numberPacket($player, 3, $money3);
                $this->numberPacket($player, 4, $money4);
                $this->numberPacket($player, 5, $money5);
                $this->numberPacket($player, 6, $money6);
            } elseif ($sbconfig->get("lines") === 7) {
                $this->numberPacket($player, 1, $money1);
                $this->numberPacket($player, 2, $money2);
                $this->numberPacket($player, 3, $money3);
                $this->numberPacket($player, 4, $money4);
                $this->numberPacket($player, 5, $money5);
                $this->numberPacket($player, 6, $money6);
                $this->numberPacket($player, 7, $money7);
            } elseif ($sbconfig->get("lines") === 8) {
                $this->numberPacket($player, 1, $money1);
                $this->numberPacket($player, 2, $money2);
                $this->numberPacket($player, 3, $money3);
                $this->numberPacket($player, 4, $money4);
                $this->numberPacket($player, 5, $money5);
                $this->numberPacket($player, 6, $money6);
                $this->numberPacket($player, 7, $money7);
                $this->numberPacket($player, 8, $money8);
            } elseif ($sbconfig->get("lines") === 9) {
                $this->numberPacket($player, 1, $money1);
                $this->numberPacket($player, 2, $money2);
                $this->numberPacket($player, 3, $money3);
                $this->numberPacket($player, 4, $money4);
                $this->numberPacket($player, 5, $money5);
                $this->numberPacket($player, 6, $money6);
                $this->numberPacket($player, 7, $money7);
                $this->numberPacket($player, 8, $money8);
                $this->numberPacket($player, 9, $money9);
            } elseif ($sbconfig->get("lines") === 10) {
                $this->numberPacket($player, 1, $money1);
                $this->numberPacket($player, 2, $money2);
                $this->numberPacket($player, 3, $money3);
                $this->numberPacket($player, 4, $money4);
                $this->numberPacket($player, 5, $money5);
                $this->numberPacket($player, 6, $money6);
                $this->numberPacket($player, 7, $money7);
                $this->numberPacket($player, 8, $money8);
                $this->numberPacket($player, 9, $money9);
                $this->numberPacket($player, 10, $money10);
            } elseif ($sbconfig->get("lines") === 11) {
                $this->numberPacket($player, 1, $money1);
                $this->numberPacket($player, 2, $money2);
                $this->numberPacket($player, 3, $money3);
                $this->numberPacket($player, 4, $money4);
                $this->numberPacket($player, 5, $money5);
                $this->numberPacket($player, 6, $money6);
                $this->numberPacket($player, 7, $money7);
                $this->numberPacket($player, 8, $money8);
                $this->numberPacket($player, 9, $money9);
                $this->numberPacket($player, 10, $money10);
                $this->numberPacket($player, 11, $money11);
            } elseif ($sbconfig->get("lines") === 12) {
                $this->numberPacket($player, 1, $money1);
                $this->numberPacket($player, 2, $money2);
                $this->numberPacket($player, 3, $money3);
                $this->numberPacket($player, 4, $money4);
                $this->numberPacket($player, 5, $money5);
                $this->numberPacket($player, 6, $money6);
                $this->numberPacket($player, 7, $money7);
                $this->numberPacket($player, 8, $money8);
                $this->numberPacket($player, 9, $money9);
                $this->numberPacket($player, 10, $money10);
                $this->numberPacket($player, 11, $money11);
                $this->numberPacket($player, 12, $money12);
            } elseif ($sbconfig->get("lines") === 13) {
                $this->numberPacket($player, 1, $money1);
                $this->numberPacket($player, 2, $money2);
                $this->numberPacket($player, 3, $money3);
                $this->numberPacket($player, 4, $money4);
                $this->numberPacket($player, 5, $money5);
                $this->numberPacket($player, 6, $money6);
                $this->numberPacket($player, 7, $money7);
                $this->numberPacket($player, 8, $money8);
                $this->numberPacket($player, 9, $money9);
                $this->numberPacket($player, 10, $money10);
                $this->numberPacket($player, 11, $money11);
                $this->numberPacket($player, 12, $money12);
                $this->numberPacket($player, 13, $money13);
            } elseif ($sbconfig->get("lines") === 14) {
                $this->numberPacket($player, 1, $money1);
                $this->numberPacket($player, 2, $money2);
                $this->numberPacket($player, 3, $money3);
                $this->numberPacket($player, 4, $money4);
                $this->numberPacket($player, 5, $money5);
                $this->numberPacket($player, 6, $money6);
                $this->numberPacket($player, 7, $money7);
                $this->numberPacket($player, 8, $money8);
                $this->numberPacket($player, 9, $money9);
                $this->numberPacket($player, 9, $money9);
                $this->numberPacket($player, 10, $money10);
                $this->numberPacket($player, 11, $money11);
                $this->numberPacket($player, 12, $money12);
                $this->numberPacket($player, 13, $money13);
                $this->numberPacket($player, 14, $money14);
            } elseif ($sbconfig->get("lines") === 15) {
                $this->numberPacket($player, 1, $money1);
                $this->numberPacket($player, 2, $money2);
                $this->numberPacket($player, 3, $money3);
                $this->numberPacket($player, 4, $money4);
                $this->numberPacket($player, 5, $money5);
                $this->numberPacket($player, 6, $money6);
                $this->numberPacket($player, 7, $money7);
                $this->numberPacket($player, 8, $money8);
                $this->numberPacket($player, 9, $money9);
                $this->numberPacket($player, 10, $money10);
                $this->numberPacket($player, 11, $money11);
                $this->numberPacket($player, 12, $money12);
                $this->numberPacket($player, 13, $money13);
                $this->numberPacket($player, 14, $money14);
                $this->numberPacket($player, 15, $money15);
            }*/


        }
    }
}