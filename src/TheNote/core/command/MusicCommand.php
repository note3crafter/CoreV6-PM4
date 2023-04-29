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
use TheNote\core\BaseAPI;
use TheNote\core\Main;
use TheNote\core\server\Music;
use TheNote\core\task\MusicTask;

class MusicCommand extends Command
{
	private $plugin;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
		$api = new BaseAPI();
		parent::__construct("music", $api->getSetting("prefix") . $api->getLang("musicprefix"), "/music");
		$this->setPermission("core.command.music");
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
		if (isset($args[0])) {
			switch ($args[0]) {
				case "next":
				case "skip":
					$this->plugin->StartNewTask();
					$sender->sendMessage($api->getSetting("prefix") . $api->getLang("musicskip"));
					return true;
					break;
				case "stop":
				case "pause":

					$this->plugin->getScheduler()->cancelAllTasks();
					$sender->sendMessage($api->getSetting("prefix") . $api->getLang("musicstop"));
					return true;
					break;
				case "start":
				case "play":
				case "resume":

					$this->plugin->StartNewTask();
					$sender->sendMessage($api->getSetting("prefix") . $api->getLang("musicplay"));
					return true;
			}
		} else {
			$sender->sendMessage($api->getSetting("prefix") . $api->getLang("musicusage"));
			return true;
		}
		return true;
	}
}