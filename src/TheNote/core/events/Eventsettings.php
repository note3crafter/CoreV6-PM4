<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//                        2017-2020

namespace TheNote\core\events;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerBucketEmptyEvent;
use pocketmine\event\player\PlayerBucketFillEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\utils\Config;
use TheNote\core\Main;

class Eventsettings implements Listener
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
    public function onBreak(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();
        $cfg = new Config($this->plugin->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        $level = $cfg->get("Break", []);
        if (in_array($player->getWorld()->getFolderName(), $level)) {
            if ($player->hasPermission("core.events.blockbreak")) {
                $event->uncancel();
            } else {
                $event->cancel();
                $player->sendPopup("§cNo Permissons to do that!");
            }
        }
    }

    public function onPlace(BlockPlaceEvent $event) {
        $player = $event->getPlayer();
        $cfg = new Config($this->plugin->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        $level = $cfg->get("Place", []);
        if (in_array($player->getWorld()->getFolderName(), $level)) {
            if ($player->hasPermission("core.events.blockplace")) {
                $event->uncancel();
            } else {
                $event->cancel();
                $player->sendPopup("§cNo Permissons to do that!");
            }
        }
    }
    public function onChat(PlayerChatEvent $event) {
        $player = $event->getPlayer();
        $cfg = new Config($this->plugin->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        $level = $cfg->get("Chat", []);
        if (in_array($player->getWorld()->getFolderName(), $level)) {
            if ($player->hasPermission("core.events.chat")) {
                $event->uncancel();
            } else {
                $event->cancel();
                $player->sendPopup("§cNo Permissons to do that!");
            }
        }
    }
    public function onDrop(PlayerDropItemEvent $event){
        $player = $event->getPlayer();
        $cfg = new Config($this->plugin->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        $level = $cfg->get("Drop", []);
        if (in_array($player->getWorld()->getFolderName(), $level)) {
            if ($player->hasPermission("core.events.drop")) {
                $event->uncancel();
            } else {
                $event->cancel();
                $player->sendPopup("§cNo Permissons to do that!");
            }
        }
    }
    public function bucketemty(PlayerBucketEmptyEvent $event) {
        $player = $event->getPlayer();
        $cfg = new Config($this->plugin->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        $level = $cfg->get("Bucketempty", []);
        if (in_array($player->getWorld()->getFolderName(), $level)) {
            if ($player->hasPermission("core.events.bucketempty")) {
                $event->uncancel();
            } else {
                $event->cancel();
                $player->sendPopup("§cNo Permissons to do that!");
            }
        }
    }
    public function bucketfill(PlayerBucketFillEvent $event) {
        $player = $event->getPlayer();
        $cfg = new Config($this->plugin->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        $level = $cfg->get("Bucketfill", []);
        if (in_array($player->getWorld()->getFolderName(), $level)) {
            if ($player->hasPermission("core.events.bucketfill")) {
                $event->uncancel();
            } else {
                $event->cancel();
                $player->sendPopup("§cNo Permissons to do that!");
            }
        }
    }
}