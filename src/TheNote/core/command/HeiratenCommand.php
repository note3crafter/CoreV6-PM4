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
use TheNote\core\Main;

class HeiratenCommand extends Command implements Listener
{

	private Main $plugin;

	public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("heiraten", $config->get("prefix") . "Heirate andere Spieler", "/heiraten", ["hei"]);
        $this->plugin = $plugin;

    }

    public function execute(CommandSender $sender, string $label, array $args)
    {
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . $lang->get("commandingame"));
            return false;
        }
        if (isset($args[0])) {
            if ($this->plugin->getServer()->getPlayerExact($args[0]) instanceof Player) {
                $victim = $this->plugin->getServer()->getPlayerExact($args[0]);

                $ba = $this->getPCFG($victim->getName(), "antrag");
                $antrag = $this->plugin->getServer()->getPlayerExact($ba);

                if (isset($antrag) AND $antrag instanceof Player) {
                    $message = str_replace("{sender}" , $sender->getNameTag(), $lang->get("heierror"));
                    $message1 = str_replace("{victim}", $victim->getName(), $message);
                    $sender->sendMessage($config->get("prefix") . $message1);
                } else {

                    if ($victim === $sender) {
                        $sender->sendMessage($config->get("error") . $lang->get("heinoyourself"));

                    } else {
                        $bh = $this->getPCFG($victim->getName(), "heiraten");
                        $hochzeit = $this->plugin->getServer()->getPlayerExact($bh);

                        if (isset($hochzeit) AND $hochzeit instanceof Player) {
                            $message = str_replace("{player}" , $hochzeit->getName(), $lang->get("heiratet"));
                            $message1 = str_replace("{victim}", $victim->getName(), $message);
                            $sender->sendMessage($config->get("heirat") . $message1);

                        } else {
                            $this->addPCFG($victim->getName(), "antrag", $sender->getName());
                            $message = str_replace("{sender}", $sender->getName(), $lang->get("heibc"));
                            $message1 = str_replace("{victim}", $victim->getName(), $message);
                            $this->plugin->getServer()->broadcastMessage($config->get("heirat") . $message1);
                            $message2 = str_replace("{sender}", $sender->getName(), $lang->get("heisuccestarget"));
                            $victim->sendMessage($config->get("heirat") . $message2);
                        }
                    }
                }
            } else {
                switch (strtolower($args[0])) {
                    case "annehmen":
                    case "accept":
                        $antrag = $this->getPCFG($sender->getName(), "antrag");
                        $victim = $this->plugin->getServer()->getPlayerExact($antrag);
                        $hei = new Config($this->plugin->getDataFolder() . Main::$heifile . $sender->getName() . ".json", Config::JSON);
                        $user = new Config($this->plugin->getDataFolder() . Main::$userfile . $sender->getName() . ".json", Config::JSON);
                        if (isset($victim) AND $victim instanceof Player) {
                            $message = str_replace("{sender}", $sender->getName(), $lang->get("heianbc"));
                            $message1 = str_replace("{victim}", $victim->getName(), $message);
                            $this->plugin->getServer()->broadcastMessage($config->get("heirat") . $message1);
                            $this->addPCFG($victim->getName(), "heiraten", $sender->getName());
                            $this->addPCFG($sender->getName(), "heiraten", $victim->getName());
                            $packet = new OnScreenTextureAnimationPacket();
                            $packet->effectId = 10;
                            $sender->getNetworkSession()->sendDataPacket($packet);
                            $victim->getNetworkSession()->sendDataPacket($packet);

                            $x = $this->getPCFG($sender->getName(), "antrag-angenommen");
                            $this->addPCFG($sender->getName(), "antrag-angenommen", ($x + 1));
                            $hei->set("Heiraten", $hei->get("Heiraten") + 1);
                            $this->addPCFG($sender->getName(), "antrag", NULL);
                            $heiv = new Config($this->plugin->getDataFolder() . Main::$userfile . $victim->getName() . ".json", Config::JSON);
                            $user->set("heistatus", true);
                            $user->save();
                            $heiv->set("heistatus", true);
                            $heiv->save();
                        } else {
                            $sender->sendMessage($config->get("heirat") . $lang->get("heianerror"));
                        }
                        break;
                    case "ablehnen":
                    case "denied":
                        $antrag = $this->getPCFG($sender->getName(), "antrag");
                        $victim = $this->plugin->getServer()->getPlayerExact($antrag);

                        if (isset($victim) AND $victim instanceof Player) {
                            $message = str_replace("{sender}", $sender->getName(), $lang->get("heiabc"));
                            $message1 = str_replace("{victim}", $victim->getName(), $message);
                            $this->plugin->getServer()->broadcastMessage($config->get("heirat") . $message1);

                            $x = $this->getPCFG($sender->getName(), "antrag-abgelehnt");
                            $this->addPCFG($sender->getName(), "antrag-abgelehnt", ($x + 1));
                            $this->addPCFG($sender->getName(), "antrag", NULL);

                        } else {
                            $sender->sendMessage($config->get("heirat") . $lang->get("heiaberror"));
                        }


                        break;
                    case "scheidung":
                    case "divorce":

                        $scheidung = $this->getPCFG($sender->getName(), "heiraten");
                        $victim = $this->plugin->getServer()->getPlayerExact($scheidung);
                        $hei = new Config($this->plugin->getDataFolder() . Main::$userfile . $sender->getName() . ".json", Config::JSON);
                        $heiv = new Config($this->plugin->getDataFolder() . Main::$userfile . $scheidung . ".json", Config::JSON);

                        //if (isset($victim) AND $victim instanceof Player) {
                        if ($hei->get("heistatus") == true){
                            $this->setScheidung($scheidung);
                            $hei->set("heistatus", false);
                            $hei->save();
                            $heiv->set("heistatus", false);
                            $heiv->save();

                            $message = str_replace("{sender}", $sender->getName(), $lang->get("heischbc"));
                            $message1 = str_replace("{victim}", $scheidung, $message);
                            $this->plugin->getServer()->broadcastMessage($config->get("heirat") . $message1);


                            $message3 = str_replace("{victim}", $scheidung, $lang->get("heischtarget"));
                            $sender->sendMessage($config->get("heirat") . $message3);
                            $packet = new OnScreenTextureAnimationPacket();
                            $packet->effectId = 20;
                            $sender->sendData((array)$packet);
                            if ($victim == null){

                            } else {
                                $victim->sendData((array)$packet);
                                $message2 = str_replace("{sender}", $sender->getName(), $lang->get("heischsender"));
                                $victim->sendMessage($config->get("heirat") . $message2);

                            }

                        } else {
                            $sender->sendMessage($config->get("heirat") . $lang->get("heischerror"));
                        }

                        break;
                    case "hilfe":
                    case "help":
                        $sender->sendMessage($config->get("info") . $lang->get("heiusage"));

                        break;
                    case "surprise":
                        $surprise = $this->getPCFG($sender->getName(), "heiraten");
                        $victim = $this->plugin->getServer()->getPlayerExact($surprise);

                        if ($victim instanceof Player) {
                            $aname = $victim->getNameTag();
                            $bname = $sender->getNameTag();

                            $b = [
                                "§a$aname §6und §a$bname §6laufen Hand in Hand richtung Sonnenuntergang!",
                                "§a$aname §6und §a$bname §6schauen sich tief in die Augen!",
                                "§a$aname §6und §a$bname §6spitzen die Lippen und ... ",
                                "§a$aname §6und §a$bname §6liegen gemeinsam im Bett...Quitch ",
                                "§a$aname §6und §a$bname §6geben sich ein Surprisefick ",
                                "§a$aname §6und §a$bname §6sind Glücklich miteinander ",
                                "§a$aname §6und §a$bname §6machen ein arschfick ",
                                "§a$aname §6und §a$bname §6spielen sich an die Glocken "
                            ];

                            $surprise = $b[rand(0, 7)];
                            $this->plugin->getServer()->broadcastMessage($config->get("heirat") . $surprise);
                        } else {
                            $sender->sendMessage($config->get("heirat") . $lang->get("heischerror"));
                        }
                        break;
                }

            }// Ende else

        } else { // Heirats command only
            $name = $sender->getName();
            $x = new Config($this->plugin->getDataFolder() . Main::$heifile . "$name.json", Config::JSON);
            $antrag = $x->get("antrag");
            $antragabgelehnt = $x->get("antrag-abgelehnt");
            $hochzeit = $x->get("heiraten");
            $hochzeithits = $x->get("heiraten-hit");
            $geschieden = $x->get("geschieden");

            $sender->sendMessage("§f======§f[§6Heiratsübersicht§f]======");
            $sender->sendMessage("§eAntrag von: §a" . $antrag);
            $sender->sendMessage("§eAnträge abgelehnt: §a" . $antragabgelehnt);
            $sender->sendMessage("§eVerheirat mit: §a" . $hochzeit);
            $sender->sendMessage("§eAktuelle Hits: §a" . $hochzeithits);
            $sender->sendMessage("§eBisher geschieden: §c" . $geschieden);
        }
        return true;
    }
    public function getPCFG($player, $a)
    {
        $pcfg = new Config($this->plugin->getDataFolder() . Main::$heifile . strtolower($player) . ".json", Config::JSON);
        $x = $pcfg->get($a);

        return $x;
    }
    public function addPCFG($player, $a, $b)
    {
        $pcfg = new Config($this->plugin->getDataFolder() . Main::$heifile . strtolower($player) . ".json", Config::JSON);
        $pcfg->set($a, $b);
        $pcfg->save();

        return true;
    }

    public function setScheidung($a)
    {
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $player = $a;
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
        $message = str_replace("{sender}", $player, $lang->get("heischbc"));
        $message1 = str_replace("{victim}", $victim, $message);
        $this->plugin->getServer()->broadcastMessage($config->get("heirat") . $message1);
        $hei->set("heistatus", false);
        $hei->save();
        $heiv->set("heistatus", false);
        $heiv->save();
        return true;
    }
}