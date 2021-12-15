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
use pocketmine\utils\Config;
use TheNote\core\Main;

class ScoreboardTask extends Task
{
	private $plugin;

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


			$pk = new SetDisplayObjectivePacket();
			$pk->displaySlot = "sidebar";
			$pk->objectiveName = "test";
			$pk->displayName = $settings->get("ueberschrift");
			$pk->criteriaName = "dummy";
			$pk->sortOrder = 0;
			$player->getNetworkSession()->sendDataPacket($pk);


			$mymoney = $money->getNested("money." . $player->getName());
			$votes = $stats->get("votes");
			$joins = $stats->get("joins");
			$break = $stats->get("break");
			$player->setScoreTag("§eVotes §f: §6$votes\n §eJoins §f: §6$joins\n§eAbgebaut §f: §6$break");

			//ScoreboardConfig zukünftig
			/*$sb = new Config($this->plugin->getDataFolder() . Main::$setup . "Scoreboard.json", Config::JSON);
			$slots = str_replace("{slots}" ,  $settings->get("slots") ,$sb->getAll());
			$oline = str_replace("{online}" ,  $online->get("players") ,$slots);
			$clan = str_replace("{clan}" ,  $gruppe->get("Clan") ,$oline);
			$marry = str_replace("{marry}" ,  $hei->get("heiraten") ,$clan);
			$coins = str_replace("{coins}" ,  $user->get("coins") ,$marry);
			$rank = str_replace("{rank}" ,   $playerdata->getNested($player->getName() . ".groupprefix") ,$coins);
			$username = str_replace("{username}" , $player->getName() ,$rank);
			if ($this->plugin->economyapi === null) {
				$money = str_replace("{money}" ,  $mymoney ,$username);
			} else {
				$money = str_replace("{money}" ,  $this->plugin->economyapi->myMoney($player) ,$username);
			}*/

			$this->numberPacket($player, 1, "§eDein Rang");
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
			$this->numberPacket($player, 12, "§f➥ §e" . $online->get("players") . "§f/§e" . $settings->get("slots") . "§f");

		}
	}
}