<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\LiftSystem;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\item\ItemIds;
use pocketmine\math\Vector3;
use pocketmine\block\Block;
use pocketmine\utils\Config;
use pocketmine\world\Position;
use pocketmine\world\sound\AnvilUseSound;
use pocketmine\world\sound\EndermanTeleportSound;
use TheNote\core\server\LiftListener;
use TheNote\core\Main;

class PlayerToggleSneakListener extends LiftListener implements Listener {

    public function onPlayerToggleSneak(PlayerToggleSneakEvent $event) {
        $settings = new Config($this->getPlugin()->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $config = new Config($this->getPlugin()->getDataFolder() . Main::$setup . "Config" . ".yml", Config::YAML);
        if(!$event->getPlayer()->isSneaking()) return;
        $block = $event->getPlayer()->getWorld()->getBlock(new Vector3($event->getPlayer()->getPosition()->getX(), $event->getPlayer()->getPosition()->getY(), $event->getPlayer()->getPosition()->getZ()));
        if($block->getId() !== ItemIds::DAYLIGHT_SENSOR && $block->getId() !== ItemIds::DAYLIGHT_SENSOR_INVERTED) return;
        if(($plot = $this->getPlugin()->myplot->getPlotByPosition($event->getPlayer()->getPosition())) === null) return;


        if (isset($this->getPlugin()->cooldown[$event->getPlayer()->getName()])) {
            if ($this->getPlugin()->cooldown[$event->getPlayer()->getName()] > time()) return;
        }

        $searchForPrivate = true;
        if($plot->owner !== $event->getPlayer()->getName() && !$event->getPlayer()->hasPermission("core.lift.admin.use")) {
            if($config->get("helperprivateLift") !== true) {
                $searchForPrivate = false;
            }else if(!$plot->isHelper($event->getPlayer()->getName())) {
                $searchForPrivate = false;
            }
        }


        if($this->getPlugin()->getElevators($block, "down", $searchForPrivate) === 0) {
			$event->getPlayer()->getWorld()->addSound($event->getPlayer()->getPosition(), new AnvilUseSound());
            $event->getPlayer()->sendMessage($settings->get("lift") . "§cDu befindest dich bereits in der niedrigsten Etage.");
            return;
        }
        $nextElevator = $this->getPlugin()->getNextElevator($block, "down", $searchForPrivate);
        if($nextElevator === null) {
			$event->getPlayer()->getWorld()->addSound($event->getPlayer()->getPosition(), new AnvilUseSound());
            $event->getPlayer()->sendMessage($settings->get("lift") . "§cDie nächste Etage wurde nicht gefunden.");
            return;
        }
        if($nextElevator === $block) {
			$event->getPlayer()->getWorld()->addSound($event->getPlayer()->getPosition(), new AnvilUseSound());
            $event->getPlayer()->sendMessage($settings->get("lift") . "§cDie nächste Etage ist nicht sicher! Daher kannst du diese nicht Betreten!");
            return;
        }
        $pos = new Position($nextElevator->getPosition()->getX() + 0.5, $nextElevator->getPosition()->getY() + 1, $nextElevator->getPosition()->getZ() + 0.5, $nextElevator->getPosition()->getWorld());
        $event->getPlayer()->teleport($pos, $event->getPlayer()->getLocation()->getYaw(), $event->getPlayer()->getLocation()->getPitch());

        $elevators = $this->getPlugin()->getElevators($block, "", $searchForPrivate);
        $floor = $this->getPlugin()->getFloor($nextElevator, $searchForPrivate);
        $event->getPlayer()->getWorld()->addSound($event->getPlayer()->getPosition(),new EndermanTeleportSound());
        $event->getPlayer()->sendTip($settings->get("lift") . "Du bist nun in der §f[§e" . $floor . "] §6Etage. §f[§e" . $floor . "§f/§e" . $elevators . "§f]");

        $this->getPlugin()->cooldown[$event->getPlayer()->getName()] = time() + 1;
    }
}