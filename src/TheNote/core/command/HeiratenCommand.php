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

use pocketmine\event\Listener;
use pocketmine\network\mcpe\protocol\OnScreenTextureAnimationPacket;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class HeiratenCommand extends Command implements Listener
{

	private Main $plugin;

	public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("heiraten", $api->getSetting("prefix") . "Heirate andere Spieler", "/heiraten", ["hei", "marry"]);
        $this->plugin = $plugin;

    }

    public function execute(CommandSender $sender, string $label, array $args)
    {
        $api = new BaseAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("commandingame"));
            return false;
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

        if (isset($args[0])) {
            $target = $api->findPlayer($sender, $args[0]);
            if ($target === null) {
                $sender->sendMessage($api->getSetting("error") . $api->getLang("playernotonline"));
            } elseif ($api->findPlayer($sender, $args[0]) instanceof Player) {
                if ($api->getMarry($sender->getName(), "marry") === true) { //Check sender is married
                    $message = str_replace("{player}", $api->getMarry($sender->getName(), "partner"), $api->getLang("heimarryerror"));
                    $sender->sendMessage($api->getSetting("heirat") . $message);
                    return false;
                } elseif ($api->getMarry($target->getName(), "marryapplication") === true) { //Check Target have a application
                    $message = str_replace("{sender}", $sender->getNameTag(), $api->getLang("heierror"));
                    $message1 = str_replace("{victim}", $target->getNameTag(), $message);
                    $sender->sendMessage($api->getSetting("heirat") . $message1);
                    return false;
                } elseif ($api->getMarry($target->getName(), "marry") === true) {//Check Target is Marryed
                    $partner = $api->getMarry($target->getName(), "partner");
                    $message = str_replace("{player}", $partner, $api->getLang("heiratet"));
                    $message1 = str_replace("{victim}", $target->getName(), $message);
                    $sender->sendMessage($api->getSetting("prefix") . $message1);
                    return false;
                } elseif ($target === $sender) { //check target is player
                    $sender->sendMessage($api->getSetting("error") . $api->getLang("heinoyourself"));
                    return false;
                    /*} elseif (isset($marry) and $marry instanceof Player) { //Check Player is married
                        $message = str_replace("{player}", $marry->getName(), $api->getLang("heiratet"));
                        $message1 = str_replace("{victim}", $target->getName(), $message);
                        $sender->sendMessage($api->getSetting("heirat") . $message1);*/
                } else {
                    $api->addMarry($target, "application", $sender->getName());
                    $api->addMarry($target, "marryapplication", true);
                    $message = str_replace("{sender}", $sender->getName(), $api->getLang("heibc"));
                    $message1 = str_replace("{victim}", $target->getName(), $message);
                    $this->plugin->getServer()->broadcastMessage($api->getSetting("heirat") . $message1);
                    $message2 = str_replace("{sender}", $sender->getName(), $api->getLang("heisuccestarget"));
                    $target->sendMessage($api->getSetting("heirat") . $message2);
                }
            } else switch (strtolower($args[0])) {
                case "annehmen":
                case "accept":
                    $data = $api->getMarry($sender->getName(), "application");
                    $target = $api->findPlayer($sender, $data);
                    if ($api->getMarry($sender->getName(), "marry") === true) {
                        $message = str_replace("{player}", $api->getMarry($sender->getName(), $api->getLang("heimarryerror")));
                        $sender->sendMessage($api->getSetting("heirat") . $message);
                        return false;
                    } elseif ($api->getMarry($sender->getName(), "marryapplication") === false) {
                        $sender->sendMessage($api->getSetting("heirat") . $api->getLang("heianerror"));
                        return false;
                    } else {
                        $packet = new OnScreenTextureAnimationPacket();
                        $packet->effectId = 10;
                        $sender->getNetworkSession()->sendDataPacket($packet);
                        $target->getNetworkSession()->sendDataPacket($packet);
                        $message = str_replace("{sender}", $sender->getName(), $api->getLang("heianbc"));
                        $message1 = str_replace("{victim}", $target->getName(), $message);
                        $this->plugin->getServer()->broadcastMessage($api->getSetting("heirat") . $message1);
                        $api->addMarry($sender, "marry", true);
                        $api->addMarry($target, "marry", true);
                        $api->addMarry($sender, "status", "Verheiratet");
                        $api->addMarry($target, "status", "Verheiratet");
                        $api->addMarry($sender, "partner", $target->getName());
                        $api->addMarry($target, "partner", $sender->getName());
                        $api->addMarry($sender, "marryapplication", false);
                        $api->addMarry($target, "marryapplication", false);
                        $api->addMarry($sender, "marrypoints", ($api->getMarry($sender->getName(), "marrypoints") + 1));
                        $api->addMarry($target, "marrypoints", ($api->getMarry($target->getName(), "marrypoints") + 1));
                        $api->addMarry($sender, "application", "Keine Anfrage");
                        $api->addMarry($target, "application", "Keine Anfrage");
                        return true;
                    }
                    break;
                case "ablehnen":
                case "denied":
                    $data = $api->getMarry($sender->getName(), "application");
                    $target = $api->findPlayer($sender, $data);
                    if ($api->getMarry($sender->getName(), "marryapplication") === false) {
                        $sender->sendMessage($api->getSetting("heirat") . $api->getLang("heianerror"));
                        return false;
                    } elseif ($api->getMarry($sender->getName(), "marry") === true) {
                        $message = str_replace("{player}", $api->getMarry($sender->getName(), $api->getLang("heimarryerror")));
                        $sender->sendMessage($api->getSetting("heirat") . $message);
                    } else {
                        $message = str_replace("{sender}", $sender->getName(), $api->getLang("heiabbc"));
                        $message1 = str_replace("{victim}", $target->getName(), $message);
                        $this->plugin->getServer()->broadcastMessage($api->getSetting("heirat") . $message1);
                        $api->addMarry($sender, "denieds", ($api->getMarry($sender->getName(), "denieds") + 1));
                        $api->addMarry($sender, "application", "Keine Anfrage");
                        $api->addMarry($sender, "status", "Single");
                        $api->addMarry($sender, "marryapplication", false);
                        return true;
                    }
                    break;
                case "scheidung":
                case "divorce":
                    $data = $api->getMarry($sender->getName(), "partner");
                    $target = $api->findPlayer($sender, $data);
                    if ($api->getMarry($sender->getName(), "marry") === false) {
                        $sender->sendMessage($api->getSetting("heirat") . $api->getLang("heischerror"));
                    } else {
                        $packet = new OnScreenTextureAnimationPacket();
                        $packet->effectId = 20;
                        $message = str_replace("{sender}", $sender->getName(), $api->getLang("heischbc"));
                        $message1 = str_replace("{victim}", $target->getName(), $message);
                        $this->plugin->getServer()->broadcastMessage($api->getSetting("heirat") . $message1);
                        $message3 = str_replace("{sender}", $target->getName(), $api->getLang("heischtarget"));
                        $sender->sendMessage($api->getSetting("heirat") . $message3);
                        if ($target === null) {
                            $sender->getNetworkSession()->sendDataPacket($packet);
                        } else {
                            $sender->getNetworkSession()->sendDataPacket($packet);
                            $target->getNetworkSession()->sendDataPacket($packet);
                            $message2 = str_replace("{victim}", $sender->getName(), $api->getLang("heischsender"));
                            $target->sendMessage($api->getSetting("heirat") . $message2);
                        }
                        $api->addMarry($sender, "marry", false);
                        $api->addMarry($target, "marry", false);
                        $api->addMarry($sender, "partner", "Kein Partner");
                        $api->addMarry($target, "partner", "Kein Partner");
                        $api->addMarry($sender, "status", "Single");
                        $api->addMarry($target, "status", "Single");
                        $api->addMarry($sender, "divorces", ($api->getMarry($sender->getName(), "divorces") + 1));
                        $api->addMarry($target, "divorces", ($api->getMarry($target->getName(), "divorces") + 1));

                    }
                    break;
                case "hilfe":
                case "help":
                    $sender->sendMessage("/marry {player}");
                    $sender->sendMessage("/marry divorce");
                    $sender->sendMessage("/marry accept");
                    $sender->sendMessage("/marry denied");
                    $sender->sendMessage("/marry surprise");
                    break;
                case "surprise":
                    $data = $api->getMarry($sender->getName(), "partner");
                    $target = $api->findPlayer($sender, $data);
                    if ($api->getMarry($sender->getName(), "marry") === false) {
                        $sender->sendMessage($api->getSetting("heirat") . $api->getLang("heischerror"));
                    } else {
                        $aname = $target->getNameTag();
                        $bname = $sender->getNameTag();
                        $b = [
                            "§a$aname §dund §a$bname §dlaufen Hand in Hand richtung Sonnenuntergang!",
                            "§a$aname §dund §a$bname §dschauen sich tief in die Augen!",
                            "§a$aname §dund §a$bname §dspitzen die Lippen und ... ",
                            "§a$aname §dund §a$bname §dliegen gemeinsam im Bett...Quitch ",
                            "§a$aname §dund §a$bname §dgeben sich ein Surprisefick ",
                            "§a$aname §dund §a$bname §dsind Glücklich miteinander ",
                            "§a$aname §dund §a$bname §dmachen ein arschfick ",
                            "§a$aname §dund §a$bname §dspielen sich an die Glocken "
                        ];
                        $surprise = $b[rand(0, 8)];
                        $this->plugin->getServer()->broadcastMessage($api->getSetting("heirat") . $surprise);
                    }
                    break;
            } /*else {
                if ($target == null) { //check is target online
                    $sender->sendMessage($api->getSetting("error") . $api->getLang("playernotonline"));
                    return false;
                }
            }*/
        } else {
            $hits = $api->getMarry($sender->getName(), "hits");
            $partner = $api->getMarry($sender->getName(), "partner");
            $application = $api->getMarry($sender->getName(), "application");
            $divorces = $api->getMarry($sender->getName(), "divorces");
            $denieds = $api->getMarry($sender->getName(), "denieds");
            $status = $api->getMarry($sender->getName(), "status");

            $sender->sendMessage("§f======§f[§aHeiratsübersicht§f]======");
            $sender->sendMessage("§eDein Partner: §a" . $partner);
            $sender->sendMessage("§eAnträge abgelehnt: §a" . $denieds);
            $sender->sendMessage("§eHeiratsstatus: §a" . $status);
            $sender->sendMessage("§eSchläge kassiert: §a" . $hits);
            $sender->sendMessage("§eBisher geschieden: §c" . $divorces);
            $sender->sendMessage("§eAntrag: §a" . $application);
            return true;
        }
        return true;
    }
}