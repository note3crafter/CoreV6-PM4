<?php

namespace TheNote\core\task;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\Main;
use TheNote\core\player\Player;

class AFKTask extends Task
{
    private Player $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
    }

    public function onRun(): void
    {
        $user = new Config(Main::getInstance()->getDataFolder() . Main::$userfile . $this->player->getName() . ".json", Config::JSON);
        $player = $this->player;
        if (!$player->hasPermission("core.command.afk.bypass")) {
            if (!$player->isOnline()) return;
            if ($user->get("afk") == null or $user->get("afk") == false) {
                if (Main::$afksesion[$player->getName()] <= 0) {
                    $player->kick("§cAFK ist nicht erlaubt!");
                }
                if (Main::$afksesion[$player->getName()] <= 20) {
                    $player->sendTip("§cDu wirst gekickt wenn du nicht spielst in §f:§6 " . Main::$afksesion[$player->getName()] . " §cSekunden.");
                }
            }
            --Main::$afksesion[$player->getName()];

        }
    }
}