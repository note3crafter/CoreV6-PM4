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
use pocketmine\network\mcpe\protocol\OnScreenTextureAnimationPacket;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class HeiratsListener implements Listener {

	private Main $plugin;

	public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }
    // Config File
    // hits : 0-10 when not marryd No Partner
    // partner : {player} or No Partner
    // application : {player} or No Application or Marryed
    // divorces : Number when nothing No Divorces
    // denieds : Number or No Denieds
    // status : Married or single
    // marry : true or false
    // marrypoints : number
    // marryapplication : true or false
    public function onMarryDamage(EntityDamageByEntityEvent $event)
    {
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $api = new BaseAPI();
        $api = new BaseAPI();
        $target = $event->getEntity();
        $damager = $event->getDamager();

        if ($target instanceof Player AND $damager instanceof Player) {
            $targ = $target->getName();
            $sender = $damager->getName();
            $shits = $api->getMarry($sender, "hits");
            $thits = $api->getMarry($targ, "hits");
            $sp = $api->getMarry($sender, "partner");
            $tp = $api->getMarry($targ, "partner");
            if ($api->getMarry($sender, "partner") === null) {
                return false;
            } else {
                $got = $api->findPlayer($sender, $tp);
            }

            if ($got instanceof Player) {
                $p = $got->getName();

                if ($sender == $p) {
                    if ($shits <= 10) {
                        $hitg = $shits + 1;
                        $api->addMarry($sender, "hits", $hitg);
                        $api->addMarry($targ, "hits", $hitg);
                    } else {
                        $packet = new OnScreenTextureAnimationPacket();
                        $packet->effectId = 20;
                        $message = str_replace("{sender}", $sender->getName(), $lang->get("heischbc"));
                        $message1 = str_replace("{victim}", $target->getName(), $message);
                        $this->plugin->getServer()->broadcastMessage($config->get("heirat") . $message1);
                        $message3 = str_replace("{sender}", $target->getName(), $lang->get("heischtarget"));
                        $sender->sendMessage($config->get("heirat") . $message3);
                        if ($target === null) {
                            $sender->getNetworkSession()->sendDataPacket($packet);
                        } else {
                            $sender->getNetworkSession()->sendDataPacket($packet);
                            $target->getNetworkSession()->sendDataPacket($packet);
                            $message2 = str_replace("{victim}", $sender->getName(), $lang->get("heischsender"));
                            $target->sendMessage($config->get("heirat") . $message2);
                        }
                        $api->addMarry($sender->getName(), "marry", false);
                        $api->addMarry($target->getName(), "marry", false);
                        $api->addMarry($sender->getName(), "partner", "Kein Partner");
                        $api->addMarry($target->getName(), "partner", "Kein Partner");
                        $api->addMarry($sender->getName(), "status", "Single");
                        $api->addMarry($target->getName(), "status", "Single");
                        $api->addMarry($sender->getName(), "divorces", ($api->getMarry($sender->getName(), "divorces") + 1));
                        $api->addMarry($target->getName(), "divorces", ($api->getMarry($target->getName(), "divorces") + 1));
                    }
                }
            }
        }
    }
}