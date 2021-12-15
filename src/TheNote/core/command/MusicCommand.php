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
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\BlockEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\world\sound\NoteSound;
use TheNote\core\Main;
use TheNote\core\server\Music;
use TheNote\core\task\MusicTask;

class MusicCommand extends Command
{
	private $plugin;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
		$config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
		$langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
		$l = $langsettings->get("Lang");
		$lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
		parent::__construct("music", $config->get("prefix") . $lang->get("musicprefix"), "/music");
		$this->setPermission("core.command.music");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args): bool
	{
		$config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
		$langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
		$l = $langsettings->get("Lang");
		$lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
		if (!$sender instanceof Player) {
			$sender->sendMessage($config->get("error") . $lang->get("commandingame"));
			return false;
		}
		if (!$this->testPermission($sender)) {
			$sender->sendMessage($config->get("error") . $lang->get("nopermission"));
			return false;
		}
		if (isset($args[0])) {
			switch ($args[0]) {
				case "next":
				case "skip":
					$this->plugin->StartNewTask();
					$sender->sendMessage($config->get("prefix") . $lang->get("musicskip"));
					return true;
					break;
				case "stop":
				case "pause":

					$this->plugin->getScheduler()->cancelAllTasks();
					$sender->sendMessage($config->get("prefix") . $lang->get("musicplay"));
					return true;
					break;
				case "start":
				case "play":
				case "resume":

					$this->plugin->StartNewTask();
					$sender->sendMessage($config->get("prefix") . $lang->get("musicplay"));
					return true;
			}
		} else {
			$sender->sendMessage($config->get("prefix") . $lang->get("musicusage"));
			return true;
		}
		return true;
	}
}