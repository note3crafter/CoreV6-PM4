<?php

namespace TheNote\core\listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use TheNote\core\Main;
use TheNote\core\utils\OnlineSQLite;

class OnlineListener implements Listener
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
    public function onJoin(PlayerJoinEvent $event)
    {
        $ot = new OnlineSQLite();
        if ($ot->hasTime($event->getPlayer()) === false) {
            $ot->registerTime($event->getPlayer());
        }
        $pn = strtolower($event->getPlayer()->getName());
        Main::$times[$pn] = time();
    }

    public function onQuit(PlayerQuitEvent $event)
    {

        $player = strtolower($event->getPlayer()->getName());
        $p = $event->getPlayer();
        if(isset(Main::$lastmoved[$player])){
            $diff = time() - Main::$lastmoved[$player];
            if(time() - Main::$lastmoved[$player] >= $this->plugin->timeout){
                Main::$times[$player] = Main::$times[$player] + $diff;
            }
            unset(Main::$lastmoved[$player]);
        }
        if (isset(Main::$times[$player])) {
            $ot = new OnlineSQLite();
            $old = $ot->getRawTime($p);
            $ot->setRawTime($p, ($old + (time() - Main::$times[$player])));
            unset(Main::$times[$player]);
        }
    }

    public function onMove(PlayerMoveEvent $event){
        $to = $event->getTo();
        $from = $event->getFrom();
        $name = strtolower($event->getPlayer()->getName());
        // Check that user is not moving from afk pool or auto run
        if($to->getYaw() !== $from->getYaw() or $to->getPitch() !== $from->getPitch()) {
            $timeout = $this->plugin->timeout;
            if (isset(Main::$lastmoved[$name])) {
                $diff = (time() - Main::$lastmoved[$name]) - $timeout;
                if ($diff >= $timeout) {
                    $event->getPlayer()->sendTip("§7Du warst untätig für §f" . (intval($diff / 60) . " §7minuten"));
                    Main::$times[$name] = Main::$times[$name] + $diff;
                } else Main::$lastmoved[$name] = time();
            }
            Main::$lastmoved[$name] = time();
        }
    }
}