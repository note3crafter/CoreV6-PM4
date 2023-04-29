<?php

namespace TheNote\core\listener;

use pocketmine\block\BaseSign;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;

class SignStatsListner implements Listener
{
    public function onInteract(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $tile = $player->getWorld()->getTile($block);
        if ($tile instanceof BaseSign) {
            $signtext = $tile->getText();
            if ($signtext[0] == "TEST") { //UHC-Meetup
                if ($signtext[1] === "§f[§dUHc§f-§aMeetup§f]") { //UHC
                    if ($signtext[2] == "Beitreten") {
                            $player->sendMessage("hat geklappt");
                    } else if ($signtext[2] == "Ingame") {
                            $player->sendMessage("hat auch geklappt");
                    } else {
                        $player->sendMessage("Du kannst diesem Match nicht mehr Beitreten!");
                    }
                }
            }
        }
    }
}