<?php

namespace TheNote\core\task;

use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;


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
        $pf = new Config(Main::getInstance()->getDataFolder() . Main::$gruppefile . $this->player->getName() . ".json", Config::JSON);
        $groups = new Config(Main::getInstance()->getDataFolder(). Main::$cloud . "groups.yml", Config::YAML);
        $api = new BaseAPI();
        $playerdata = new Config(Main::getInstance()->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        $player = $this->player;
        if (!$player->hasPermission("core.command.afk.bypass")) {
            if (!$player->isOnline()) return;
            if ($user->get("afk") === null or $user->get("afk") === false) {
                //if (!Main::$afksesion[$player->getName() === null]) return;
                if (Main::$afksesion[$player->getName()] === null) {
                    $player->sendMessage($api->getSetting("afk") . $api->getLang("afknowafk"));
                    $player->setImmobile(true);
                    $user->set("afkmove", true);
                    $user->set("afk", true);
                    $user->save();
                    $playergroup = $playerdata->getNested($player->getName() . ".group");
                    $nametag = str_replace("{name}", $pf->get("Nickname"), $groups->getNested("Groups.{$playergroup}.nametag"));
                    $displayname = str_replace("{name}", $pf->get("Nickname"), $groups->getNested("Groups.{$playerdata->getNested($player->getName().".group")}.displayname"));
                    $player->setDisplayName("§f[§6AFK§f] " . $displayname);
                    $player->setNameTag("§f[§6AFK§f] " . $nametag);
                }
                if (Main::$afksesion[$player->getName()] <= 20) {
                    $player->sendTip("§cDu wirst automatich in den AFK Modus gesetzt wenn du nicht spielst in §f:§6 " . Main::$afksesion[$player->getName()] . " §cSekunden.");
                }
            }
            --Main::$afksesion[$player->getName()];
        }
    }
}