<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\listener;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use TheNote\core\Main;

class HeiratsListener implements Listener {

	private Main $plugin;

	public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }

    public function setScheidung($a)
    {
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $player = $a->getName();
        $x = new Config($this->plugin->getDataFolder() . Main::$heifile . strtolower($player) . ".json", Config::JSON);
        $hochzeit = $x->get("heiraten");

        $got = $this->plugin->getServer()->getPlayerExact($hochzeit);
        $victim = $got->getName();

        $v = new Config($this->plugin->getDataFolder() . Main::$heifile . strtolower($victim) . ".json", Config::JSON);
        $hei = new Config($this->plugin->getDataFolder() . Main::$userfile . $player . ".json", Config::JSON);
        $heiv = new Config($this->plugin->getDataFolder() . Main::$userfile . $victim . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);


        $v->set("heiraten", "No Partners");
        $v->set("heiraten-hit", 0);
        $vgesch = $v->get("geschieden");
        $v->set("geschieden", $vgesch + 1);
        $v->save();

        $x->set("heiraten", "No Partners");
        $x->set("heiraten-hit", 0);
        $xgesch = $x->get("geschieden");
        $x->set("geschieden", $xgesch + 1);
        $x->save();

        $hei->set("heistatus", false);
        $hei->save();
        $heiv->set("heistatus", false);
        $heiv->save();
        $message = str_replace("{sender}", $player, $lang->get("heischbc"));
        $message1 = str_replace("{victim}", $victim, $message);
        $this->plugin->getServer()->broadcastMessage($config->get("heirat") . $message1);
        return true;
    }
    public function onHochzeitDamage(EntityDamageByEntityEvent $event)
    {
        $victim = $event->getEntity();
        $damager = $event->getDamager();

        if ($victim instanceof Player AND $damager instanceof Player) {
            $name = $victim->getName();
            $dname = $damager->getName();

            $v = new Config($this->plugin->getDataFolder() . Main::$heifile . strtolower($name) . ".json", Config::JSON);
            $x = new Config($this->plugin->getDataFolder() . Main::$heifile . strtolower($dname) . ".json", Config::JSON);
            $ve = $v->get("heiraten");
            $vag = $v->get("heiraten-hit");
            $got = $this->plugin->getServer()->getPlayerExact($ve);

            if ($got instanceof Player) {
                $gotcha = $got->getName();

                if ($dname == $gotcha) {
                    if ($vag <= 10) {
                        $hitg = $vag + 1;
                        $this->addPCFG($name, "heiraten-hit", $hitg);
                    } else {
                        $this->setScheidung($victim);
                    }
                }
            }
        }
    }
    public function addPCFG($player, $a, $b)
    {
        $pcfg = new Config($this->plugin->getDataFolder() . Main::$heifile . strtolower($player) . ".json", Config::JSON);
        $pcfg->set($a, $b);
        $pcfg->save();

        return true;
    }

}