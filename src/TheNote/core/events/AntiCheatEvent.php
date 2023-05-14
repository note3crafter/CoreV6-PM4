<?php

namespace TheNote\core\events;

use pocketmine\block\Slab;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class AntiCheatEvent implements Listener
{
    public $User = [];
    private $breakTimes = [];

    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onPlayerMove(PlayerMoveEvent $event): void
    {
        $player = $event->getPlayer();
        $Oldy = $event->getFrom()->getY();
        $Newy = $event->getTo()->getY();
        $user = new Config($this->plugin->getDataFolder() . Main::$userfile . $player->getName() . ".json", Config::JSON);
        if($user->get("afk") === true) {
            if($player->getGamemode() === GameMode::SPECTATOR()) return;
            if($player->getNetworkSession()->getPing() < 200) return;
            if ($player->getAllowFlight() === false) {
                if ($Oldy <= $Newy) {
                    if ($player->GetInAirTicks() > 40) {
                        $maxY = $player->getWorld()->getHighestBlockAt(floor($player->getPosition()->getX()), floor($player->getPosition()->getZ()));
                        if ($Newy - 2 > $maxY) {
                            if (count($player->getEffects()->all()) == 0) {
                                $this->alert("Fly", $player->getName());
                            }
                        }
                    }
                }
            }
        }
        $x = $event->getFrom()->getX() - $event->getTo()->getX();
        //$y = $event->getFrom()->getY() - $event->getTo()->getY();
        $z = $event->getFrom()->getZ() - $event->getTo()->getZ();
        if($user->get("afk") === false) {
            if($player->getGamemode() === GameMode::SPECTATOR()) return;
            if($player->getNetworkSession()->getPing() >= 100) return;
            if($player->isGliding()) return;
            if($player->hasBlockCollision()) return;
            //if($player->getPosition()->getWorld()->getBlock()->getId() == new Slab()) return;
            if (abs($x) >= 2) {
                if (count($player->getEffects()->all()) == 0) {
                    if ($player->getAllowFlight() === true) {
                        return;
                    }
                    $this->alert("Speed", $player->getName());
                } else {
                    return;
                }
            }
        }
        if($user->get("afk") === false) {
            if($player->getGamemode() === GameMode::SPECTATOR()) return;
            if($player->getNetworkSession()->getPing() >= 100) return;
            if($player->isGliding()) return;
            if($player->hasBlockCollision()) return;
            ///if($player->getPosition()->getWorld()->getBlock()->getId() == new Slab()) return;
            if (abs($z) >= 2) {
                if (count($player->getEffects()->all()) === 0) {
                    if ($player->getAllowFlight() === true) {
                        return;
                    }
                    $this->alert("Speed", $player->getName());
                } else {
                    return;
                }
            }
        }

    }

    public function alert(string $cheat, string $player): void
    {
        foreach (Server::getInstance()->getOnlinePlayers() as $staff) {
            //if ($staff->hasPermission("core.events.anticheat")) {
                $this->getUser($staff, $cheat, $player);
            //}
        }
    }

    public function getUser(Player $staff, string $cheat, string $player): void
    {
        $dcsettings = new Config($this->plugin->getDataFolder() . Main::$setup . "discordsettings" . ".yml", Config::YAML);
        $api = new BaseAPI();
        $staff->sendMessage($api->getSetting("info") . "§cDer Spieler§f :§c $player §fnutzt §c" . $cheat . "hack§f!!!");
        $name = $staff->getName();
        $chatprefix = $dcsettings->get("chatprefix");
        if ($dcsettings->get("DC") === true) {
            $ar = getdate();
            $time = $ar['hours'] . ":" . $ar['minutes'];
            $format = "**__ " . $chatprefix . " : {time} : (ACHTUNG) {player} nutzt : $cheat hack!!!__**";
            $msg = str_replace("{time}", $time, str_replace("{player}", $player, $format));
            $this->plugin->sendMessage($name, $msg);
        }
    }
}