<?php

namespace TheNote\core\command;

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\formapi\CustomForm;
use TheNote\core\formapi\SimpleForm;
use TheNote\core\Main;

class ScoreboardCommand extends Command
{
    public static $sbprefix = "§f[§dScore§eboard§f]";
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        $api = new BaseAPI();
        parent::__construct("sb", $api->getSetting("prefix") . $api->getLang("scorebprefix"), "/sb", ["scoreboard", "sboard"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        $settings = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $user = new Config($this->plugin->getDataFolder() . Main::$userfile . $sender->getName() . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($settings->get("error") . $api->getLang("commandingame"));
            return false;
        }
        $this->scoreboardmenu($sender);
        return true;
    }

    public function scoreboardmenu($sender)
    {
        $user = new Config($this->plugin->getDataFolder() . Main::$userfile . $sender->getName() . ".json", Config::JSON);
        $form = new SimpleForm(function (Player $player, int $data = null) {
            $api = new BaseAPI();
            $user = new Config($this->plugin->getDataFolder() . Main::$userfile . $player->getName() . ".json", Config::JSON);
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    if ($user->get("sbcustom") === true) {
                        $player->sendMessage($api->getSetting("prefix") . "§dDein Scoreboard wurde §cDeaktiviert §dda du versucht hast das Standart Scoreboard zu §aaktivieren!");
                        $user->set("sbcustom", false);
                        $user->save();
                        $pk = new RemoveObjectivePacket();
                        $pk->objectiveName = "custom";
                        $player->getNetworkSession()->sendDataPacket($pk);
                        $this->scoreboardmenu($player);
                        return false;
                    } elseif ($user->get("sb") === false) {
                        $player->sendMessage($api->getSetting("prefix") . "§dDu hast dein Scoreboard §aAktiviert");
                        $user->set("sb", true);
                        $user->save();
                    } else {
                        $player->sendMessage($api->getSetting("prefix") . "§dDu hast dein Scoreboard §cDeaktiviert");
                        $user->set("sb", false);
                        $user->save();
                        $pk = new RemoveObjectivePacket();
                        $pk->objectiveName = "standart";
                        $player->getNetworkSession()->sendDataPacket($pk);
                    }
                    break;
                case 1:
                    if ($user->get("customscore") === true) {
                        if ($user->get("sb") === true) {
                            $player->sendMessage($api->getSetting("prefix") . "§dDein Scoreboard wurde §cDeaktiviert §dda du versucht hast das custom Scoreboard zu §aaktivieren!");
                            $user->set("sb", false);
                            $user->save();
                            $pk = new RemoveObjectivePacket();
                            $pk->objectiveName = "standart";
                            $player->getNetworkSession()->sendDataPacket($pk);
                            $this->scoreboardmenu($player);
                            return false;
                        } elseif ($user->get("sbcustom") === false) {
                            $player->sendMessage($api->getSetting("prefix") . "§dDu hast dein Customscoreboard §aAktiviert");
                            $user->set("sbcustom", true);
                            $user->save();
                        } else {
                            $player->sendMessage($api->getSetting("prefix") . "§dDu hast dein Customscoreboard §cDeaktiviert");
                            $user->set("sbcustom", false);
                            $user->save();
                            $pk = new RemoveObjectivePacket();
                            $pk->objectiveName = "custom";
                            $player->getNetworkSession()->sendDataPacket($pk);
                        }
                    } elseif ($user->get("customscore") === false) {
                        $player->sendMessage($api->getSetting("error") . "§cDu hast dir noch kein Eigenes Scoreboard erstellt!");
                    }
                    break;
                case 2:
                    if ($user->get("sb") === true) {
                        $user->set("sb", false);
                        $user->save();
                    }
                    if ($user->get("sbcustom") === true) {
                        $user->set("sbcustom", false);
                        $user->save();
                    }
                    $pk = new RemoveObjectivePacket();
                    $pk->objectiveName = "standart";
                    $player->getNetworkSession()->sendDataPacket($pk);
                    $pk = new RemoveObjectivePacket();
                    $pk->objectiveName = "custom";
                    $player->getNetworkSession()->sendDataPacket($pk);
                    $this->custommenu($player);

            }
        });
        $form->setTitle(ScoreboardCommand::$sbprefix
        );
        if ($user->get("sb") === true) {
            $form->addButton("§5Standart\n§aAktiviert",);
        } else {
            $form->addButton("§5Standart\n§cDeaktiviert",);
        }
        if ($user->get("customscore") === false) {
            $form->addButton("§5Custom\n§4Kein Customscoreboard");
        } elseif ($user->get("sbcustom") === true) {
            $form->addButton("§5Custom\n§aAktiviert");
        } else {
            $form->addButton("§5Custom\n§cDeaktiviert");
        }
        $form->addButton("§5Dein CustomScoreboard"/*, 0, "textures/other/crafting"*/);
        $form->addButton("§5Abbrechen"/*, 0, "textures/other/Cybercraft"*/);
        $form->sendToPlayer($sender);
        return $form;
    }

    public function custommenu($sender)
    {
        $form = new SimpleForm(function (Player $player, int $data = null) {
            $playersb = new Config($this->plugin->getDataFolder() . Main::$scoreboardfile . $player->getName() . ".json", Config::JSON);
            $user = new Config($this->plugin->getDataFolder() . Main::$userfile . $player->getName() . ".json", Config::JSON);
            $result = $data;
            $api = new BaseAPI();
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    $user->set("customscore", false);
                    $user->set("sbcustom", false);
                    $user->save();
                    $this->select($player);
                    $playersb->set("title", "");
                    $playersb->set("line1", "");
                    $playersb->set("line2", "");
                    $playersb->set("line3", "");
                    $playersb->set("line4", "");
                    $playersb->set("line5", "");
                    $playersb->set("line6", "");
                    $playersb->set("line7", "");
                    $playersb->set("line8", "");
                    $playersb->set("line9", "");
                    $playersb->set("line10", "");
                    $playersb->set("line11", "");
                    $playersb->set("line12", "");
                    $playersb->set("line13", "");
                    $playersb->set("line14", "");
                    $playersb->set("line15", "");
                    $playersb->set("l1", false);
                    $playersb->set("l2", false);
                    $playersb->set("l3", false);
                    $playersb->set("l4", false);
                    $playersb->set("l5", false);
                    $playersb->set("l6", false);
                    $playersb->set("l7", false);
                    $playersb->set("l8", false);
                    $playersb->set("l9", false);
                    $playersb->set("l10", false);
                    $playersb->set("l11", false);
                    $playersb->set("l12", false);
                    $playersb->set("l13", false);
                    $playersb->set("l14", false);
                    $playersb->set("l15", false);
                    $playersb->save();
                    break;
                case 1:
                    $this->syntax($player);
                    break;
                case 2:
                    if ($user->get("customscore") === false) {
                        $player->sendMessage($api->getSetting("error") . "§cDu hast dir noch kein Scoreboard erstellt!");
                    } else {
                        $this->scoreedit($player);
                    }
                    break;
                case 3:
                    $user->set("customscore", false);
                    $user->set("sbcustom", false);
                    $user->save();
                    $playersb->set("title", "");
                    $playersb->set("line1", "");
                    $playersb->set("line2", "");
                    $playersb->set("line3", "");
                    $playersb->set("line4", "");
                    $playersb->set("line5", "");
                    $playersb->set("line6", "");
                    $playersb->set("line7", "");
                    $playersb->set("line8", "");
                    $playersb->set("line9", "");
                    $playersb->set("line10", "");
                    $playersb->set("line11", "");
                    $playersb->set("line12", "");
                    $playersb->set("line13", "");
                    $playersb->set("line14", "");
                    $playersb->set("line15", "");
                    $playersb->set("l1", false);
                    $playersb->set("l2", false);
                    $playersb->set("l3", false);
                    $playersb->set("l4", false);
                    $playersb->set("l5", false);
                    $playersb->set("l6", false);
                    $playersb->set("l7", false);
                    $playersb->set("l8", false);
                    $playersb->set("l9", false);
                    $playersb->set("l10", false);
                    $playersb->set("l11", false);
                    $playersb->set("l12", false);
                    $playersb->set("l13", false);
                    $playersb->set("l14", false);
                    $playersb->set("l15", false);
                    $playersb->save();
                    $player->sendMessage($api->getSetting("prefix") . "§dDu hast dein Scoreboard erfolgreich gelöscht!");
                    break;
                case 4:
                    $this->scoreboardmenu($player);
                    break;
            }
        });
        $form->setTitle(ScoreboardCommand::$sbprefix);
        $form->setContent("§eBearbeite hier dein Scoreboard oder Lösche es!");
        $form->addButton("§5Erstellen\n§cDein altes wird dabei gelöscht!");
        $form->addButton("§5Syntaxes");
        $form->addButton("§5Bearbeiten");
        $form->addButton("§5Löschen");
        $form->addButton("§5Zurück");
        $form->addButton("§5Abbrechen");
        $form->sendToPlayer($sender);
        return $form;
    }

    public function syntax($sender)
    {
        $form = new SimpleForm(function (Player $player, int $data = null) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    $this->custommenu($player);
                    break;

            }
        });
        $form->setTitle(ScoreboardCommand::$sbprefix);
        $form->setContent("§5Siehe hier die Syntaxes fürs Scoreboard" . "\n\n\n" .
            "§dBeispiel für eine Zeile §f:§e Meine Position §f:§e {x} §f:§e {y} §f:§e {z}" . "\n\n" .
            "§e{clan} §d<- Dein Clanname" . "\n" .
            "§e{marry} §d<- Dein Partner" . "\n" .
            "§e{rank} §d<- Dein Rang" . "\n" .
            "§e{coins} §d<- Deine Coins" . "\n" .
            "§e{money} §d<- Dein Geld" . "\n" .
            "§e{ping} §d<- Dein Ping" . "\n" .
            "§e{tps} §d<- Server TPS" . "\n" .
            "§e{name} §d<- Dein Spielername" . "\n" .
            "§e{online} §d<- Online Spieler" . "\n" .
            "§e{max_online} §d<- Maximale Slots" . "\n" .
            "§e{world} §d<- Weltname" . "\n" .
            "§e{x} §d<- Deine Position X" . "\n" .
            "§e{y} §d<- Deine Position Y" . "\n" .
            "§e{z} §d<- Deine Position Z" . "\n" .
            "§e{ip} §d<- Deine IP" . "\n" .
            "§e{uid} §d<- Deine UUID" . "\n" .
            "§e{xuid} §d<- Deine Xbox ID" . "\n" .
            "§e{health} §d<- Deine Leben" . "\n" .
            "§e{max_health} §d<- Deine Maximale Leben" . "\n" .
            "§e{food} §d<- Dein Futterstand" . "\n" .
            "§e{max_food} §d<- Dein maximaler Futterstand" . "\n" .
            "§e{gamemode} §d<- Dein Spielmodus" . "\n" .
            "§e{scale} §d<- Deine Größe" . "\n" .
            "§e {xplevel} §d<- Dein XP Level" . "\n" .
            "§e{id} §d<- Item ID" . "\n" .
            "§e{meta} §d<- Item Meta" . "\n" .
            "§e{count} §d<- Item Menge" . "\n\n" .
            "§eStatistiken von dir" . "\n" .
            "§e{kicks} §d<- Deine Kicks" . "\n" .
            "§e{joins} §d<- Deine Joins" . "\n" .
            "§e{breaks} §d<- Deine Abgebauten Blöcke" . "\n" .
            "§e{places} §d<- Deine Gesetzten Blöcke" . "\n" .
            "§e{drops} §d<- Deine Gesammelten Items" . "\n" .
            "§e{picks} §d<- Deine aufgesammelten Items" . "\n" .
            "§e{interacts} §d<- Deine Interactionen" . "\n" .
            "§e{messages} §d<- Deine Nachrrichten" . "\n" .
            "§e{votes} §d<- Deine Votes" . "\n" .
            "§e{flymeters} §d<- Deine geflogenen Meter" . "\n" .
            "§e{walkmeters} §d<- Deine gelaufene Meter" . "\n" .
            "§e{deaths} §d<- Deine Deaths" . "\n" .
            "§e{consumes} §d<- Deine Konsumierten Items" . "\n"
        );
        $form->addButton("§5Zurück");
        $form->sendToPlayer($sender);
        return $form;
    }

    public function endscoreedit($sender)
    {
        $playersb = new Config($this->plugin->getDataFolder() . Main::$scoreboardfile . $sender->getName() . ".json", Config::JSON);
        $form = new SimpleForm(function (Player $player, int $data = null) {
            $user = new Config($this->plugin->getDataFolder() . Main::$userfile . $player->getName() . ".json", Config::JSON);
            $api = new BaseAPI();
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    $this->scoreedit($player);
                    break;
                case 1:
                    $player->sendMessage($api->getSetting("prefix") . "§dDu hast dein Scoreboard erfolgreich geupdatet!");
                    break;
                case 2:
                    $this->custommenu($player);
                    break;
            }
        });
        $form->setTitle(ScoreboardCommand::$sbprefix);
        $form->setContent("Hier ist dein Scoreboard. Du kannst es hinterher nochmal bearbeiten!" . "\n\n" .
            "§4Titel §e:§f" . $playersb->get("titel") . "\n" .
            "§41 §e:§f" . $playersb->get("line1") . "\n" .
            "§42 §e:§f" . $playersb->get("line2") . "\n" .
            "§43 §e:§f" . $playersb->get("line3") . "\n" .
            "§44 §e:§f" . $playersb->get("line4") . "\n" .
            "§45 §e:§f" . $playersb->get("line5") . "\n" .
            "§46 §e:§f" . $playersb->get("line6") . "\n" .
            "§47 §e:§f" . $playersb->get("line7") . "\n" .
            "§48 §e:§f" . $playersb->get("line8") . "\n" .
            "§49 §e:§f" . $playersb->get("line9") . "\n" .
            "§410§e:§f" . $playersb->get("line10") . "\n" .
            "§411§e:§f" . $playersb->get("line11") . "\n" .
            "§412§e:§f" . $playersb->get("line12") . "\n" .
            "§413§e:§f" . $playersb->get("line13") . "\n" .
            "§414§e:§f" . $playersb->get("line14") . "\n" .
            "§415§e:§f" . $playersb->get("line15")
        );
        $form->addButton("§5Bearbeiten");
        $form->addButton("§5Bestätigen");
        $form->addButton("§5Zurück");
        $form->addButton("§5Abbrechen");
        $form->sendToPlayer($sender);
        return $form;
    }

    public function endscore($sender)
    {
        $playersb = new Config($this->plugin->getDataFolder() . Main::$scoreboardfile . $sender->getName() . ".json", Config::JSON);
        $form = new SimpleForm(function (Player $player, int $data = null) {
            $user = new Config($this->plugin->getDataFolder() . Main::$userfile . $player->getName() . ".json", Config::JSON);
            $api = new BaseAPI();
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    $user->set("customscore", true);
                    $user->set("sbcustom", true);
                    $user->set("sb", false);
                    $user->save();
                    $player->sendMessage($api->getSetting("prefix") . "§dDu hast dein custom Scoreboard erfolgreich gesetzt.");
                    break;
                case 1:
                    $this->custommenu($player);
                    break;
            }
        });
        $form->setTitle(ScoreboardCommand::$sbprefix);
        $form->setContent("Hier ist dein Scoreboard. Du kannst es hinterher nochmal bearbeiten!" . "\n\n" .
            "§4Titel §e:§f" . $playersb->get("titel") . "\n" .
            "§41 §e:§f" . $playersb->get("line1") . "\n" .
            "§42 §e:§f" . $playersb->get("line2") . "\n" .
            "§43 §e:§f" . $playersb->get("line3") . "\n" .
            "§44 §e:§f" . $playersb->get("line4") . "\n" .
            "§45 §e:§f" . $playersb->get("line5") . "\n" .
            "§46 §e:§f" . $playersb->get("line6") . "\n" .
            "§47 §e:§f" . $playersb->get("line7") . "\n" .
            "§48 §e:§f" . $playersb->get("line8") . "\n" .
            "§49 §e:§f" . $playersb->get("line9") . "\n" .
            "§410§e:§f" . $playersb->get("line10") . "\n" .
            "§411§e:§f" . $playersb->get("line11") . "\n" .
            "§412§e:§f" . $playersb->get("line12") . "\n" .
            "§413§e:§f" . $playersb->get("line13") . "\n" .
            "§414§e:§f" . $playersb->get("line14") . "\n" .
            "§415§e:§f" . $playersb->get("line15")
        );
        $form->addButton("§5Bestätigen");
        $form->addButton("§5Zurück");
        $form->sendToPlayer($sender);
        return $form;
    }

    public function select($sender)
    {
        $form = new CustomForm(function (Player $sender, array $data = null) {

            if ($data === null) {
                return true;
            }
            switch ($data[1]) {
                case 0:
                    $this->menuone($sender);
                    break;
                case 1:
                    $this->menutwo($sender);
                    break;
                case 2:
                    $this->menutree($sender);
                    break;
                case 3:
                    $this->menufour($sender);
                    break;
                case 4:
                    $this->menufive($sender);
                    break;
                case 5:
                    $this->menusix($sender);
                    break;
                case 6:
                    $this->menuseven($sender);
                    break;
                case 7:
                    $this->menuacht($sender);
                    break;
                case 8:
                    $this->menunine($sender);
                    break;
                case 9:
                    $this->menuten($sender);
                    break;
                case 10:
                    $this->menuelf($sender);
                    break;
                case 11:
                    $this->menueinstwo($sender);
                    break;
                case 12:
                    $this->menueinstree($sender);
                    break;
                case 13:
                    $this->menueinsfour($sender);
                    break;
                case 14:
                    $this->menueinsfive($sender);
                    break;
            }
        });
        $form->setTitle(ScoreboardCommand::$sbprefix);
        $form->addLabel("§eWähle hier wie viele Zeilen du möchtest.");
        $form->addStepSlider("§dLines §f", ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15"], 5);
        $form->sendToPlayer($sender);
        return $form;
    }

    public function scoreedit($sender)
    {
        $playersb = new Config($this->plugin->getDataFolder() . Main::$scoreboardfile . $sender->getName() . ".json", Config::JSON);
        $form = new CustomForm(function (Player $sender, array $data = null) {
            $playersb = new Config($this->plugin->getDataFolder() . Main::$scoreboardfile . $sender->getName() . ".json", Config::JSON);

            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($data[1]) {
                case 0:
                    $playersb->set("title", "§f>>>[§5Cyber§eCraft§f]<<<");
                    $playersb->save();
                    break;
                case 1:
                    $playersb->set("title", "§5Cyber§eCraft");
                    $playersb->save();
                    break;
                case 2:
                    $playersb->set("title", "§f===§f[§5Cyber§eCraft§f]===");
                    $playersb->save();
                    break;
                case 3:
                    $playersb->set("title", "§f[§5Cyber§eCraft§f]");
                    $playersb->save();
                    break;
            }
            if ($playersb->get("l1") === true) {
                if ($data[2] === null) {
                    return false;
                } else {
                    $playersb->set("line1", "$data[2]");
                    $playersb->save();
                }
            } elseif ($playersb->get("l2") === true) {
                if ($data[3] === null) {
                    return false;
                } else {
                    $playersb->set("line2", "$data[3]");
                    $playersb->save();
                }
            } elseif ($playersb->get("l3") === true) {
                if ($data[4] === null) {
                    return false;
                } else {
                    $playersb->set("line3", "$data[4]");
                    $playersb->save();
                }
            } elseif ($playersb->get("l4") === true) {
                if ($data[5] === null) {
                    return false;
                } else {
                    $playersb->set("line4", "$data[5]");
                    $playersb->save();
                }
            } elseif ($playersb->get("l5") === true) {
                if ($data[6] === null) {
                    return false;
                } else {
                    $playersb->set("line5", "$data[6]");
                    $playersb->save();
                }
            } elseif ($playersb->get("l6") === true) {
                if ($data[7] === null) {
                    return false;
                } else {
                    $playersb->set("line6", "$data[7]");
                    $playersb->save();
                }
            } elseif ($playersb->get("l7") === true) {
                if ($data[8] === null) {
                    return false;
                } else {
                    $playersb->set("line7", "$data[8]");
                    $playersb->save();
                }
            } elseif ($playersb->get("l8") === true) {
                if ($data[9] === null) {
                    return false;
                } else {
                    $playersb->set("line8", "$data[9]");
                }
            } elseif ($playersb->get("l9") === true) {
                if ($data[10] === null) {
                    return false;
                } else {
                    $playersb->set("line9", "$data[10]");
                    $playersb->save();
                }
            } elseif ($playersb->get("l10") === true) {
                if ($data[11] === null) {
                    return false;
                } else {
                    $playersb->set("line10", "$data[11]");
                }
            } elseif ($playersb->get("l11") === true) {
                if ($data[12] === null) {
                    return false;
                } else {
                    $playersb->set("line11", "$data[12]");
                    $playersb->save();
                }
            } elseif ($playersb->get("l12") === true) {
                if ($data[13] === null) {
                    return false;
                } else {
                    $playersb->set("line12", "$data[13]");
                    $playersb->save();
                }
            } elseif ($playersb->get("l13") === true) {
                if ($data[14] === null) {
                    return false;
                } else {
                    $playersb->set("line13", "$data[14]");
                    $playersb->save();
                }
            } elseif ($playersb->get("l14") === true) {
                if ($data[15] === null) {
                    return false;
                } else {
                    $playersb->set("line14", "$data[15]");
                    $playersb->save();
                }
            } elseif ($playersb->get("l15") === true) {
                if ($data[16] === null) {
                    return false;
                } else {
                    $playersb->set("line15", "$data[16]");
                    $playersb->save();
                }
            }
            $playersb->save();
            $this->endscoreedit($sender);
        });
        $form->setTitle(ScoreboardCommand::$sbprefix);
        $form->addLabel("§eBearbeite hier dein Scoreboard.");
        $form->addDropdown("§aTitels", ["§f>>>[§5Cyber§eCraft§f]<<<", "§5Cyber§eCraft", "§f===§f[§5Cyber§eCraft§f]===", "§f[§5Cyber§eCraft§f]"]);
        if ($playersb->get("l1") === true) {
            $form->addInput("§eZeile 1 §f:\n§c➥ Voher §f: §d" . $playersb->get("line1"), "Bearbeite diese Zeile", $playersb->get("line1"));
        }
        if ($playersb->get("l2") === true) {
            $form->addInput("§eZeile 2 §f:\n§c➥ Voher §f: §d" . $playersb->get("line2"), "Bearbeite diese Zeile", $playersb->get("line2"));
        }
        if ($playersb->get("l3") === true) {
            $form->addInput("§eZeile 3 §f:\n§c➥ Voher §f: §d" . $playersb->get("line3"), "Bearbeite diese Zeile", $playersb->get("line3"));
        }
        if ($playersb->get("l4") === true) {
            $form->addInput("§eZeile 4 §f:\n§c➥ Voher §f: §d" . $playersb->get("line4"), "Bearbeite diese Zeile", $playersb->get("line4"));
        }
        if ($playersb->get("l5") === true) {
            $form->addInput("§eZeile 5 §f:\n§c➥ Voher §f: §d" . $playersb->get("line5"), "Bearbeite diese Zeile", $playersb->get("line5"));
        }
        if ($playersb->get("l6") === true) {
            $form->addInput("§eZeile 6 §f:\n§c➥ Voher §f: §d" . $playersb->get("line6"), "Bearbeite diese Zeile", $playersb->get("line6"));
        }
        if ($playersb->get("l7") === true) {
            $form->addInput("§eZeile 7 §f:\n§c➥ Voher §f: §d" . $playersb->get("line7"), "Bearbeite diese Zeile", $playersb->get("line7"));
        }
        if ($playersb->get("l8") === true) {
            $form->addInput("§eZeile 8 §f:\n§c➥ Voher §f: §d" . $playersb->get("line8"), "Bearbeite diese Zeile", $playersb->get("line8"));
        }
        if ($playersb->get("l9") === true) {
            $form->addInput("§eZeile 9 §f:\n§c➥ Voher §f: §d" . $playersb->get("line9"), "Bearbeite diese Zeile", $playersb->get("line9"));
        }
        if ($playersb->get("l10") === true) {
            $form->addInput("§eZeile 10 §f:\n§c➥ Voher §f: §d" . $playersb->get("line10"), "Bearbeite diese Zeile", $playersb->get("line10"));
        }
        if ($playersb->get("l11") === true) {
            $form->addInput("§eZeile 11 §f:\n§c➥ Voher §f: §d" . $playersb->get("line11"), "Bearbeite diese Zeile", $playersb->get("line11"));
        }
        if ($playersb->get("l12") === true) {
            $form->addInput("§eZeile 12 §f:\n§c➥ Voher §f: §d" . $playersb->get("line12"), "Bearbeite diese Zeile", $playersb->get("line12"));
        }
        if ($playersb->get("l13") === true) {
            $form->addInput("§eZeile 13 §f:\n§c➥ Voher §f: §d" . $playersb->get("line13"), "Bearbeite diese Zeile", $playersb->get("line13"));
        }
        if ($playersb->get("l14") === true) {
            $form->addInput("§eZeile 14 §f:\n§c➥ Voher §f: §d" . $playersb->get("line14"), "Bearbeite diese Zeile", $playersb->get("line14"));
        }
        if ($playersb->get("l15") === true) {
            $form->addInput("§eZeile 15 §f:\n§c➥ Voher §f: §d" . $playersb->get("line15"), "Bearbeite diese Zeile", $playersb->get("line15"));
        }
        $form->sendToPlayer($sender);
        return $form;
    }

    /*public function custom($sender)
    {
        $form = new CustomForm(function (Player $sender, array $data = null) {

            if ($data === null) {
                return true;
            }
            switch ($data[1]) {
                case 0:
                    $sender->sendMessage("stepslider1");
                    break;
                case 1:
                    $sender->sendMessage("stepslider2");
                    break;
                case 2:
                    $sender->sendMessage("stepslider3");
                    break;

            }
            switch ($data[2]) {
                case 0:
                    $sender->sendMessage("dropdown1");
                    break;
                case 1:
                    $sender->sendMessage("dropdown2");
                    break;
                case 2:
                    $sender->sendMessage("dropdown3");
                    break;
            }
            if($data[3] === true){
                $sender->sendMessage("toggle true");
            } else {
                $sender->sendMessage("toggle false");
            }
            $sender->sendMessage("input:" . $data[4]);
            switch ($data[5]) {
                case 0:
                    $sender->sendMessage("slider1");
                    break;
                case 1:
                    $sender->sendMessage("slider2");
                    break;
                case 2:
                    $sender->sendMessage("slider3");
                    break;
            }
        });
        $form->setTitle("title");
        $form->addLabel("Label");
        $form->addStepSlider("§dLines §f", ["1", "2", "3"], 2); //data1
        $form->addDropdown("Dropdown", ["Test1", "Test2", "Test3"]); //data2
        $form->addToggle("Toggle"); //data3
        $form->addInput("input", "test"); //data4
        $form->addSlider("slider", 1, 5); //data5
        $form->sendToPlayer($sender);
        return $form;
    }*/

    public function menuone($sender)
    {
        $form = new CustomForm(function (Player $sender, array $data = null) {
            $playersb = new Config($this->plugin->getDataFolder() . Main::$scoreboardfile . $sender->getName() . ".json", Config::JSON);

            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($data[1]) {
                case 0:
                    $playersb->set("title", "§f>>>[§5Cyber§eCraft§f]<<<");
                    $playersb->save();
                    break;
                case 1:
                    $playersb->set("title", "§5Cyber§eCraft");
                    $playersb->save();
                    break;
                case 2:
                    $playersb->set("title", "§f===§f[§5Cyber§eCraft§f]===");
                    $playersb->save();
                    break;
                case 3:
                    $playersb->set("title", "§f[§5Cyber§eCraft§f]");
                    $playersb->save();
                    break;
            }
            $playersb->set("line1", "$data[2]");
            $playersb->set("l1", true);
            $playersb->save();
            $this->endscore($sender);
        });
        $form->setTitle(ScoreboardCommand::$sbprefix);
        $form->addLabel("§eBearbeite hier dein Scoreboard.");
        $form->addDropdown("§aTitels", ["§f>>>[§5Cyber§eCraft§f]<<<", "§5Cyber§eCraft", "§f===§f[§5Cyber§eCraft§f]===", "§f[§5Cyber§eCraft§f]"]);
        $form->addInput("§eHier die Zeile", "Zeile 1");
        $form->sendToPlayer($sender);
        return $form;
    }

    public function menutwo($sender)
    {
        $form = new CustomForm(function (Player $sender, array $data = null) {
            $playersb = new Config($this->plugin->getDataFolder() . Main::$scoreboardfile . $sender->getName() . ".json", Config::JSON);

            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($data[1]) {
                case 0:
                    $playersb->set("title", "§f>>>[§5Cyber§eCraft§f]<<<");
                    $playersb->save();
                    break;
                case 1:
                    $playersb->set("title", "§5Cyber§eCraft");
                    $playersb->save();
                    break;
                case 2:
                    $playersb->set("title", "§f===§f[§5Cyber§eCraft§f]===");
                    $playersb->save();
                    break;
                case 3:
                    $playersb->set("title", "§f[§5Cyber§eCraft§f]");
                    $playersb->save();
                    break;
            }
            $playersb->set("line1", "$data[2]");
            $playersb->set("l1", true);
            $playersb->set("line2", "$data[3]");
            $playersb->set("l2", true);
            $playersb->save();
            $this->endscore($sender);
        });
        $form->setTitle(ScoreboardCommand::$sbprefix
        );
        $form->addLabel("§eBearbeite hier dein Scoreboard.");
        $form->addDropdown("§aTitels", ["§f>>>[§5Cyber§eCraft§f]<<<", "§5Cyber§eCraft", "§f===§f[§5Cyber§eCraft§f]===", "§f[§5Cyber§eCraft§f]"]);
        $form->addInput("§eHier die Zeilen", "Zeile 1");
        $form->addInput("", "Zeile 2");
        $form->sendToPlayer($sender);
        return $form;
    }

    public function menutree($sender)
    {
        $form = new CustomForm(function (Player $sender, array $data = null) {
            $playersb = new Config($this->plugin->getDataFolder() . Main::$scoreboardfile . $sender->getName() . ".json", Config::JSON);

            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($data[1]) {
                case 0:
                    $playersb->set("title", "§f>>>[§5Cyber§eCraft§f]<<<");
                    $playersb->save();
                    break;
                case 1:
                    $playersb->set("title", "§5Cyber§eCraft");
                    $playersb->save();
                    break;
                case 2:
                    $playersb->set("title", "§f===§f[§5Cyber§eCraft§f]===");
                    $playersb->save();
                    break;
                case 3:
                    $playersb->set("title", "§f[§5Cyber§eCraft§f]");
                    $playersb->save();
                    break;
            }
            $playersb->set("line1", "$data[2]");
            $playersb->set("l1", true);
            $playersb->set("line2", "$data[3]");
            $playersb->set("l2", true);
            $playersb->set("line3", "$data[4]");
            $playersb->set("l3", true);
            $playersb->save();
            $this->endscore($sender);
        });
        $form->setTitle(ScoreboardCommand::$sbprefix);
        $form->addLabel("§eBearbeite hier dein Scoreboard.");
        $form->addDropdown("§aTitels", ["§f>>>[§5Cyber§eCraft§f]<<<", "§5Cyber§eCraft", "§f===§f[§5Cyber§eCraft§f]===", "§f[§5Cyber§eCraft§f]"]);
        $form->addInput("§eHier die Zeilen", "Zeile 1");
        $form->addInput("", "Zeile 2");
        $form->addInput("", "Zeile 3");
        $form->sendToPlayer($sender);
        return $form;
    }

    public function menufour($sender)
    {
        $form = new CustomForm(function (Player $sender, array $data = null) {
            $playersb = new Config($this->plugin->getDataFolder() . Main::$scoreboardfile . $sender->getName() . ".json", Config::JSON);

            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($data[1]) {
                case 0:
                    $playersb->set("title", "§f>>>[§5Cyber§eCraft§f]<<<");
                    $playersb->save();
                    break;
                case 1:
                    $playersb->set("title", "§5Cyber§eCraft");
                    $playersb->save();
                    break;
                case 2:
                    $playersb->set("title", "§f===§f[§5Cyber§eCraft§f]===");
                    $playersb->save();
                    break;
                case 3:
                    $playersb->set("title", "§f[§5Cyber§eCraft§f]");
                    $playersb->save();
                    break;
            }
            $playersb->set("line1", "$data[2]");
            $playersb->set("l1", true);
            $playersb->set("line2", "$data[3]");
            $playersb->set("l2", true);
            $playersb->set("line3", "$data[4]");
            $playersb->set("l3", true);
            $playersb->set("line4", "$data[5]");
            $playersb->set("l4", true);
            $playersb->save();
            $this->endscore($sender);
        });
        $form->setTitle(ScoreboardCommand::$sbprefix);
        $form->addLabel("§eBearbeite hier dein Scoreboard.");
        $form->addDropdown("§aTitels", ["§f>>>[§5Cyber§eCraft§f]<<<", "§5Cyber§eCraft", "§f===§f[§5Cyber§eCraft§f]===", "§f[§5Cyber§eCraft§f]"]);
        $form->addInput("§eHier die Zeilen", "Zeile 1");
        $form->addInput("", "Zeile 2");
        $form->addInput("", "Zeile 3");
        $form->addInput("", "Zeile 4");
        $form->sendToPlayer($sender);
        return $form;
    }

    public function menufive($sender)
    {
        $form = new CustomForm(function (Player $sender, array $data = null) {
            $playersb = new Config($this->plugin->getDataFolder() . Main::$scoreboardfile . $sender->getName() . ".json", Config::JSON);

            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($data[1]) {
                case 0:
                    $playersb->set("title", "§f>>>[§5Cyber§eCraft§f]<<<");
                    $playersb->save();
                    break;
                case 1:
                    $playersb->set("title", "§5Cyber§eCraft");
                    $playersb->save();
                    break;
                case 2:
                    $playersb->set("title", "§f===§f[§5Cyber§eCraft§f]===");
                    $playersb->save();
                    break;
                case 3:
                    $playersb->set("title", "§f[§5Cyber§eCraft§f]");
                    $playersb->save();
                    break;
            }
            $playersb->set("line1", "$data[2]");
            $playersb->set("l1", true);
            $playersb->set("line2", "$data[3]");
            $playersb->set("l2", true);
            $playersb->set("line3", "$data[4]");
            $playersb->set("l3", true);
            $playersb->set("line4", "$data[5]");
            $playersb->set("l4", true);
            $playersb->set("line5", "$data[6]");
            $playersb->set("l5", true);
            $playersb->save();
            $this->endscore($sender);
        });
        $form->setTitle(ScoreboardCommand::$sbprefix);
        $form->addLabel("§eBearbeite hier dein Scoreboard.");
        $form->addDropdown("§aTitels", ["§f>>>[§5Cyber§eCraft§f]<<<", "§5Cyber§eCraft", "§f===§f[§5Cyber§eCraft§f]===", "§f[§5Cyber§eCraft§f]"]);
        $form->addInput("§eHier die Zeilen", "Zeile 1");
        $form->addInput("", "Zeile 2");
        $form->addInput("", "Zeile 3");
        $form->addInput("", "Zeile 4");
        $form->addInput("", "Zeile 5");
        $form->sendToPlayer($sender);
        return $form;
    }

    public function menusix($sender)
    {
        $form = new CustomForm(function (Player $sender, array $data = null) {
            $playersb = new Config($this->plugin->getDataFolder() . Main::$scoreboardfile . $sender->getName() . ".json", Config::JSON);

            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($data[1]) {
                case 0:
                    $playersb->set("title", "§f>>>[§5Cyber§eCraft§f]<<<");
                    $playersb->save();
                    break;
                case 1:
                    $playersb->set("title", "§5Cyber§eCraft");
                    $playersb->save();
                    break;
                case 2:
                    $playersb->set("title", "§f===§f[§5Cyber§eCraft§f]===");
                    $playersb->save();
                    break;
                case 3:
                    $playersb->set("title", "§f[§5Cyber§eCraft§f]");
                    $playersb->save();
                    break;
            }
            $playersb->set("line1", "$data[2]");
            $playersb->set("l1", true);
            $playersb->set("line2", "$data[3]");
            $playersb->set("l2", true);
            $playersb->set("line3", "$data[4]");
            $playersb->set("l3", true);
            $playersb->set("line4", "$data[5]");
            $playersb->set("l4", true);
            $playersb->set("line5", "$data[6]");
            $playersb->set("l5", true);
            $playersb->set("line6", "$data[7]");
            $playersb->set("l6", true);
            $playersb->save();
            $this->endscore($sender);
        });
        $form->setTitle(ScoreboardCommand::$sbprefix);
        $form->addLabel("§eBearbeite hier dein Scoreboard.");
        $form->addDropdown("§aTitels", ["§f>>>[§5Cyber§eCraft§f]<<<", "§5Cyber§eCraft", "§f===§f[§5Cyber§eCraft§f]===", "§f[§5Cyber§eCraft§f]"]);
        $form->addInput("§eHier die Zeilen", "Zeile 1");
        $form->addInput("", "Zeile 2");
        $form->addInput("", "Zeile 3");
        $form->addInput("", "Zeile 4");
        $form->addInput("", "Zeile 5");
        $form->addInput("", "Zeile 6");
        $form->sendToPlayer($sender);
        return $form;
    }

    public function menuseven($sender)
    {
        $form = new CustomForm(function (Player $sender, array $data = null) {
            $playersb = new Config($this->plugin->getDataFolder() . Main::$scoreboardfile . $sender->getName() . ".json", Config::JSON);

            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($data[1]) {
                case 0:
                    $playersb->set("title", "§f>>>[§5Cyber§eCraft§f]<<<");
                    $playersb->save();
                    break;
                case 1:
                    $playersb->set("title", "§5Cyber§eCraft");
                    $playersb->save();
                    break;
                case 2:
                    $playersb->set("title", "§f===§f[§5Cyber§eCraft§f]===");
                    $playersb->save();
                    break;
                case 3:
                    $playersb->set("title", "§f[§5Cyber§eCraft§f]");
                    $playersb->save();
                    break;
            }
            $playersb->set("line1", "$data[2]");
            $playersb->set("l1", true);
            $playersb->set("line2", "$data[3]");
            $playersb->set("l2", true);
            $playersb->set("line3", "$data[4]");
            $playersb->set("l3", true);
            $playersb->set("line4", "$data[5]");
            $playersb->set("l4", true);
            $playersb->set("line5", "$data[6]");
            $playersb->set("l5", true);
            $playersb->set("line6", "$data[7]");
            $playersb->set("l6", true);
            $playersb->set("line7", "$data[8]");
            $playersb->set("l7", true);
            $playersb->save();
            $this->endscore($sender);
        });
        $form->setTitle(ScoreboardCommand::$sbprefix);
        $form->addLabel("§eBearbeite hier dein Scoreboard.");
        $form->addDropdown("§aTitels", ["§f>>>[§5Cyber§eCraft§f]<<<", "§5Cyber§eCraft", "§f===§f[§5Cyber§eCraft§f]===", "§f[§5Cyber§eCraft§f]"]);
        $form->addInput("§eHier die Zeilen", "Zeile 1");
        $form->addInput("", "Zeile 2");
        $form->addInput("", "Zeile 3");
        $form->addInput("", "Zeile 4");
        $form->addInput("", "Zeile 5");
        $form->addInput("", "Zeile 6");
        $form->addInput("", "Zeile 7");
        $form->sendToPlayer($sender);
        return $form;
    }

    public function menuacht($sender)
    {
        $form = new CustomForm(function (Player $sender, array $data = null) {
            $playersb = new Config($this->plugin->getDataFolder() . Main::$scoreboardfile . $sender->getName() . ".json", Config::JSON);

            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($data[1]) {
                case 0:
                    $playersb->set("title", "§f>>>[§5Cyber§eCraft§f]<<<");
                    $playersb->save();
                    break;
                case 1:
                    $playersb->set("title", "§5Cyber§eCraft");
                    $playersb->save();
                    break;
                case 2:
                    $playersb->set("title", "§f===§f[§5Cyber§eCraft§f]===");
                    $playersb->save();
                    break;
                case 3:
                    $playersb->set("title", "§f[§5Cyber§eCraft§f]");
                    $playersb->save();
                    break;
            }
            $playersb->set("line1", "$data[2]");
            $playersb->set("l1", true);
            $playersb->set("line2", "$data[3]");
            $playersb->set("l2", true);
            $playersb->set("line3", "$data[4]");
            $playersb->set("l3", true);
            $playersb->set("line4", "$data[5]");
            $playersb->set("l4", true);
            $playersb->set("line5", "$data[6]");
            $playersb->set("l5", true);
            $playersb->set("line6", "$data[7]");
            $playersb->set("l6", true);
            $playersb->set("line7", "$data[8]");
            $playersb->set("l7", true);
            $playersb->set("line8", "$data[9]");
            $playersb->set("l8", true);
            $playersb->save();
            $this->endscore($sender);
        });
        $form->setTitle(ScoreboardCommand::$sbprefix);
        $form->addLabel("§eBearbeite hier dein Scoreboard.");
        $form->addDropdown("§aTitels", ["§f>>>[§5Cyber§eCraft§f]<<<", "§5Cyber§eCraft", "§f===§f[§5Cyber§eCraft§f]===", "§f[§5Cyber§eCraft§f]"]);
        $form->addInput("§eHier die Zeilen", "Zeile 1");
        $form->addInput("", "Zeile 2");
        $form->addInput("", "Zeile 3");
        $form->addInput("", "Zeile 4");
        $form->addInput("", "Zeile 5");
        $form->addInput("", "Zeile 6");
        $form->addInput("", "Zeile 7");
        $form->addInput("", "Zeile 8");
        $form->sendToPlayer($sender);
        return $form;
    }

    public function menunine($sender)
    {
        $form = new CustomForm(function (Player $sender, array $data = null) {
            $playersb = new Config($this->plugin->getDataFolder() . Main::$scoreboardfile . $sender->getName() . ".json", Config::JSON);

            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($data[1]) {
                case 0:
                    $playersb->set("title", "§f>>>[§5Cyber§eCraft§f]<<<");
                    $playersb->save();
                    break;
                case 1:
                    $playersb->set("title", "§5Cyber§eCraft");
                    $playersb->save();
                    break;
                case 2:
                    $playersb->set("title", "§f===§f[§5Cyber§eCraft§f]===");
                    $playersb->save();
                    break;
                case 3:
                    $playersb->set("title", "§f[§5Cyber§eCraft§f]");
                    $playersb->save();
                    break;
            }
            $playersb->set("line1", "$data[2]");
            $playersb->set("l1", true);
            $playersb->set("line2", "$data[3]");
            $playersb->set("l2", true);
            $playersb->set("line3", "$data[4]");
            $playersb->set("l3", true);
            $playersb->set("line4", "$data[5]");
            $playersb->set("l4", true);
            $playersb->set("line5", "$data[6]");
            $playersb->set("l5", true);
            $playersb->set("line6", "$data[7]");
            $playersb->set("l6", true);
            $playersb->set("line7", "$data[8]");
            $playersb->set("l7", true);
            $playersb->set("line8", "$data[9]");
            $playersb->set("l8", true);
            $playersb->set("line9", "$data[10]");
            $playersb->set("l9", true);
            $playersb->save();
            $this->endscore($sender);
        });
        $form->setTitle(ScoreboardCommand::$sbprefix);
        $form->addLabel("§eBearbeite hier dein Scoreboard.");
        $form->addDropdown("§aTitels", ["§f>>>[§5Cyber§eCraft§f]<<<", "§5Cyber§eCraft", "§f===§f[§5Cyber§eCraft§f]===", "§f[§5Cyber§eCraft§f]"]);
        $form->addInput("§eHier die Zeilen", "Zeile 1");
        $form->addInput("", "Zeile 2");
        $form->addInput("", "Zeile 3");
        $form->addInput("", "Zeile 4");
        $form->addInput("", "Zeile 5");
        $form->addInput("", "Zeile 6");
        $form->addInput("", "Zeile 7");
        $form->addInput("", "Zeile 8");
        $form->addInput("", "Zeile 9");
        $form->sendToPlayer($sender);
        return $form;
    }

    public function menuten($sender)
    {
        $form = new CustomForm(function (Player $sender, array $data = null) {
            $playersb = new Config($this->plugin->getDataFolder() . Main::$scoreboardfile . $sender->getName() . ".json", Config::JSON);

            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($data[1]) {
                case 0:
                    $playersb->set("title", "§f>>>[§5Cyber§eCraft§f]<<<");
                    $playersb->save();
                    break;
                case 1:
                    $playersb->set("title", "§5Cyber§eCraft");
                    $playersb->save();
                    break;
                case 2:
                    $playersb->set("title", "§f===§f[§5Cyber§eCraft§f]===");
                    $playersb->save();
                    break;
                case 3:
                    $playersb->set("title", "§f[§5Cyber§eCraft§f]");
                    $playersb->save();
                    break;
            }
            $playersb->set("line1", "$data[2]");
            $playersb->set("l1", true);
            $playersb->set("line2", "$data[3]");
            $playersb->set("l2", true);
            $playersb->set("line3", "$data[4]");
            $playersb->set("l3", true);
            $playersb->set("line4", "$data[5]");
            $playersb->set("l4", true);
            $playersb->set("line5", "$data[6]");
            $playersb->set("l5", true);
            $playersb->set("line6", "$data[7]");
            $playersb->set("l6", true);
            $playersb->set("line7", "$data[8]");
            $playersb->set("l7", true);
            $playersb->set("line8", "$data[9]");
            $playersb->set("l8", true);
            $playersb->set("line9", "$data[10]");
            $playersb->set("l9", true);
            $playersb->set("line10", "$data[11]");
            $playersb->set("l10", true);
            $playersb->save();
            $this->endscore($sender);
        });
        $form->setTitle(ScoreboardCommand::$sbprefix);
        $form->addLabel("§eBearbeite hier dein Scoreboard.");
        $form->addDropdown("§aTitels", ["§f>>>[§5Cyber§eCraft§f]<<<", "§5Cyber§eCraft", "§f===§f[§5Cyber§eCraft§f]===", "§f[§5Cyber§eCraft§f]"]);
        $form->addInput("§eHier die Zeilen", "Zeile 1");
        $form->addInput("", "Zeile 2");
        $form->addInput("", "Zeile 3");
        $form->addInput("", "Zeile 4");
        $form->addInput("", "Zeile 5");
        $form->addInput("", "Zeile 6");
        $form->addInput("", "Zeile 7");
        $form->addInput("", "Zeile 8");
        $form->addInput("", "Zeile 9");
        $form->addInput("", "Zeile 10");
        $form->sendToPlayer($sender);
        return $form;
    }

    public function menuelf($sender)
    {
        $form = new CustomForm(function (Player $sender, array $data = null) {
            $playersb = new Config($this->plugin->getDataFolder() . Main::$scoreboardfile . $sender->getName() . ".json", Config::JSON);

            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($data[1]) {
                case 0:
                    $playersb->set("title", "§f>>>[§5Cyber§eCraft§f]<<<");
                    $playersb->save();
                    break;
                case 1:
                    $playersb->set("title", "§5Cyber§eCraft");
                    $playersb->save();
                    break;
                case 2:
                    $playersb->set("title", "§f===§f[§5Cyber§eCraft§f]===");
                    $playersb->save();
                    break;
                case 3:
                    $playersb->set("title", "§f[§5Cyber§eCraft§f]");
                    $playersb->save();
                    break;
            }
            $playersb->set("line1", "$data[2]");
            $playersb->set("l1", true);
            $playersb->set("line2", "$data[3]");
            $playersb->set("l2", true);
            $playersb->set("line3", "$data[4]");
            $playersb->set("l3", true);
            $playersb->set("line4", "$data[5]");
            $playersb->set("l4", true);
            $playersb->set("line5", "$data[6]");
            $playersb->set("l5", true);
            $playersb->set("line6", "$data[7]");
            $playersb->set("l6", true);
            $playersb->set("line7", "$data[8]");
            $playersb->set("l7", true);
            $playersb->set("line8", "$data[9]");
            $playersb->set("l8", true);
            $playersb->set("line9", "$data[10]");
            $playersb->set("l9", true);
            $playersb->set("line10", "$data[11]");
            $playersb->set("l10", true);
            $playersb->set("line11", "$data[12]");
            $playersb->set("l11", true);
            $playersb->save();
            $this->endscore($sender);
        });
        $form->setTitle(ScoreboardCommand::$sbprefix);
        $form->addLabel("§eBearbeite hier dein Scoreboard.");
        $form->addDropdown("§aTitels", ["§f>>>[§5Cyber§eCraft§f]<<<", "§5Cyber§eCraft", "§f===§f[§5Cyber§eCraft§f]===", "§f[§5Cyber§eCraft§f]"]);
        $form->addInput("§eHier die Zeilen", "Zeile 1");
        $form->addInput("", "Zeile 2");
        $form->addInput("", "Zeile 3");
        $form->addInput("", "Zeile 4");
        $form->addInput("", "Zeile 5");
        $form->addInput("", "Zeile 6");
        $form->addInput("", "Zeile 7");
        $form->addInput("", "Zeile 8");
        $form->addInput("", "Zeile 9");
        $form->addInput("", "Zeile 10");
        $form->addInput("", "Zeile 11");
        $form->sendToPlayer($sender);
        return $form;
    }

    public function menueinstwo($sender)
    {
        $form = new CustomForm(function (Player $sender, array $data = null) {
            $playersb = new Config($this->plugin->getDataFolder() . Main::$scoreboardfile . $sender->getName() . ".json", Config::JSON);

            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($data[1]) {
                case 0:
                    $playersb->set("title", "§f>>>[§5Cyber§eCraft§f]<<<");
                    $playersb->save();
                    break;
                case 1:
                    $playersb->set("title", "§5Cyber§eCraft");
                    $playersb->save();
                    break;
                case 2:
                    $playersb->set("title", "§f===§f[§5Cyber§eCraft§f]===");
                    $playersb->save();
                    break;
                case 3:
                    $playersb->set("title", "§f[§5Cyber§eCraft§f]");
                    $playersb->save();
                    break;
            }
            $playersb->set("line1", "$data[2]");
            $playersb->set("l1", true);
            $playersb->set("line2", "$data[3]");
            $playersb->set("l2", true);
            $playersb->set("line3", "$data[4]");
            $playersb->set("l3", true);
            $playersb->set("line4", "$data[5]");
            $playersb->set("l4", true);
            $playersb->set("line5", "$data[6]");
            $playersb->set("l5", true);
            $playersb->set("line6", "$data[7]");
            $playersb->set("l6", true);
            $playersb->set("line7", "$data[8]");
            $playersb->set("l7", true);
            $playersb->set("line8", "$data[9]");
            $playersb->set("l8", true);
            $playersb->set("line9", "$data[10]");
            $playersb->set("l9", true);
            $playersb->set("line10", "$data[11]");
            $playersb->set("l10", true);
            $playersb->set("line11", "$data[12]");
            $playersb->set("l11", true);
            $playersb->set("line12", "$data[13]");
            $playersb->set("l12", true);
            $playersb->save();
            $this->endscore($sender);
        });
        $form->setTitle(ScoreboardCommand::$sbprefix);
        $form->addLabel("§eBearbeite hier dein Scoreboard.");
        $form->addDropdown("§aTitels", ["§f>>>[§5Cyber§eCraft§f]<<<", "§5Cyber§eCraft", "§f===§f[§5Cyber§eCraft§f]===", "§f[§5Cyber§eCraft§f]"]);
        $form->addInput("§eHier die Zeilen", "Zeile 1");
        $form->addInput("", "Zeile 2");
        $form->addInput("", "Zeile 3");
        $form->addInput("", "Zeile 4");
        $form->addInput("", "Zeile 5");
        $form->addInput("", "Zeile 6");
        $form->addInput("", "Zeile 7");
        $form->addInput("", "Zeile 8");
        $form->addInput("", "Zeile 9");
        $form->addInput("", "Zeile 10");
        $form->addInput("", "Zeile 11");
        $form->addInput("", "Zeile 12");
        $form->sendToPlayer($sender);
        return $form;
    }

    public function menueinstree($sender)
    {
        $form = new CustomForm(function (Player $sender, array $data = null) {
            $playersb = new Config($this->plugin->getDataFolder() . Main::$scoreboardfile . $sender->getName() . ".json", Config::JSON);

            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($data[1]) {
                case 0:
                    $playersb->set("title", "§f>>>[§5Cyber§eCraft§f]<<<");
                    $playersb->save();
                    break;
                case 1:
                    $playersb->set("title", "§5Cyber§eCraft");
                    $playersb->save();
                    break;
                case 2:
                    $playersb->set("title", "§f===§f[§5Cyber§eCraft§f]===");
                    $playersb->save();
                    break;
                case 3:
                    $playersb->set("title", "§f[§5Cyber§eCraft§f]");
                    $playersb->save();
                    break;
            }
            $playersb->set("line1", "$data[2]");
            $playersb->set("l1", true);
            $playersb->set("line2", "$data[3]");
            $playersb->set("l2", true);
            $playersb->set("line3", "$data[4]");
            $playersb->set("l3", true);
            $playersb->set("line4", "$data[5]");
            $playersb->set("l4", true);
            $playersb->set("line5", "$data[6]");
            $playersb->set("l5", true);
            $playersb->set("line6", "$data[7]");
            $playersb->set("l6", true);
            $playersb->set("line7", "$data[8]");
            $playersb->set("l7", true);
            $playersb->set("line8", "$data[9]");
            $playersb->set("l8", true);
            $playersb->set("line9", "$data[10]");
            $playersb->set("l9", true);
            $playersb->set("line10", "$data[11]");
            $playersb->set("l10", true);
            $playersb->set("line11", "$data[12]");
            $playersb->set("l11", true);
            $playersb->set("line12", "$data[13]");
            $playersb->set("l12", true);
            $playersb->set("line13", "$data[14]");
            $playersb->set("l13", true);
            $playersb->save();
            $this->endscore($sender);
        });
        $form->setTitle(ScoreboardCommand::$sbprefix);
        $form->addLabel("§eBearbeite hier dein Scoreboard.");
        $form->addDropdown("§aTitels", ["§f>>>[§5Cyber§eCraft§f]<<<", "§5Cyber§eCraft", "§f===§f[§5Cyber§eCraft§f]===", "§f[§5Cyber§eCraft§f]"]);
        $form->addInput("§eHier die Zeilen", "Zeile 1");
        $form->addInput("", "Zeile 2");
        $form->addInput("", "Zeile 3");
        $form->addInput("", "Zeile 4");
        $form->addInput("", "Zeile 5");
        $form->addInput("", "Zeile 6");
        $form->addInput("", "Zeile 7");
        $form->addInput("", "Zeile 8");
        $form->addInput("", "Zeile 9");
        $form->addInput("", "Zeile 10");
        $form->addInput("", "Zeile 11");
        $form->addInput("", "Zeile 12");
        $form->addInput("", "Zeile 13");
        $form->sendToPlayer($sender);
        return $form;
    }

    public function menueinsfour($sender)
    {
        $form = new CustomForm(function (Player $sender, array $data = null) {
            $playersb = new Config($this->plugin->getDataFolder() . Main::$scoreboardfile . $sender->getName() . ".json", Config::JSON);

            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($data[1]) {
                case 0:
                    $playersb->set("title", "§f>>>[§5Cyber§eCraft§f]<<<");
                    $playersb->save();
                    break;
                case 1:
                    $playersb->set("title", "§5Cyber§eCraft");
                    $playersb->save();
                    break;
                case 2:
                    $playersb->set("title", "§f===§f[§5Cyber§eCraft§f]===");
                    $playersb->save();
                    break;
                case 3:
                    $playersb->set("title", "§f[§5Cyber§eCraft§f]");
                    $playersb->save();
                    break;
            }
            $playersb->set("line1", "$data[2]");
            $playersb->set("l1", true);
            $playersb->set("line2", "$data[3]");
            $playersb->set("l2", true);
            $playersb->set("line3", "$data[4]");
            $playersb->set("l3", true);
            $playersb->set("line4", "$data[5]");
            $playersb->set("l4", true);
            $playersb->set("line5", "$data[6]");
            $playersb->set("l5", true);
            $playersb->set("line6", "$data[7]");
            $playersb->set("l6", true);
            $playersb->set("line7", "$data[8]");
            $playersb->set("l7", true);
            $playersb->set("line8", "$data[9]");
            $playersb->set("l8", true);
            $playersb->set("line9", "$data[10]");
            $playersb->set("l9", true);
            $playersb->set("line10", "$data[11]");
            $playersb->set("l10", true);
            $playersb->set("line11", "$data[12]");
            $playersb->set("l11", true);
            $playersb->set("line12", "$data[13]");
            $playersb->set("l12", true);
            $playersb->set("line13", "$data[14]");
            $playersb->set("l13", true);
            $playersb->set("line14", "$data[15]");
            $playersb->set("l14", true);
            $playersb->save();
            $this->endscore($sender);
        });
        $form->setTitle(ScoreboardCommand::$sbprefix);
        $form->addLabel("§eBearbeite hier dein Scoreboard.");
        $form->addDropdown("§aTitels", ["§f>>>[§5Cyber§eCraft§f]<<<", "§5Cyber§eCraft", "§f===§f[§5Cyber§eCraft§f]===", "§f[§5Cyber§eCraft§f]"]);
        $form->addInput("§eHier die Zeilen", "Zeile 1");
        $form->addInput("", "Zeile 2");
        $form->addInput("", "Zeile 3");
        $form->addInput("", "Zeile 4");
        $form->addInput("", "Zeile 5");
        $form->addInput("", "Zeile 6");
        $form->addInput("", "Zeile 7");
        $form->addInput("", "Zeile 8");
        $form->addInput("", "Zeile 9");
        $form->addInput("", "Zeile 10");
        $form->addInput("", "Zeile 11");
        $form->addInput("", "Zeile 12");
        $form->addInput("", "Zeile 13");
        $form->addInput("", "Zeile 14");
        $form->sendToPlayer($sender);
        return $form;
    }

    public function menueinsfive($sender)
    {
        $form = new CustomForm(function (Player $sender, array $data = null) {
            $playersb = new Config($this->plugin->getDataFolder() . Main::$scoreboardfile . $sender->getName() . ".json", Config::JSON);

            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($data[1]) {
                case 0:
                    $playersb->set("title", "§f>>>[§5Cyber§eCraft§f]<<<");
                    $playersb->save();
                    break;
                case 1:
                    $playersb->set("title", "§5Cyber§eCraft");
                    $playersb->save();
                    break;
                case 2:
                    $playersb->set("title", "§f===§f[§5Cyber§eCraft§f]===");
                    $playersb->save();
                    break;
                case 3:
                    $playersb->set("title", "§f[§5Cyber§eCraft§f]");
                    $playersb->save();
                    break;
            }
            $playersb->save();
            $playersb->set("line1", "$data[2]");
            $playersb->set("l1", true);
            $playersb->set("line2", "$data[3]");
            $playersb->set("l2", true);
            $playersb->set("line3", "$data[4]");
            $playersb->set("l3", true);
            $playersb->set("line4", "$data[5]");
            $playersb->set("l4", true);
            $playersb->set("line5", "$data[6]");
            $playersb->set("l5", true);
            $playersb->set("line6", "$data[7]");
            $playersb->set("l6", true);
            $playersb->set("line7", "$data[8]");
            $playersb->set("l7", true);
            $playersb->set("line8", "$data[9]");
            $playersb->set("l8", true);
            $playersb->set("line9", "$data[10]");
            $playersb->set("l9", true);
            $playersb->set("line10", "$data[11]");
            $playersb->set("l10", true);
            $playersb->set("line11", "$data[12]");
            $playersb->set("l11", true);
            $playersb->set("line12", "$data[13]");
            $playersb->set("l12", true);
            $playersb->set("line13", "$data[14]");
            $playersb->set("l13", true);
            $playersb->set("line14", "$data[15]");
            $playersb->set("l14", true);
            $playersb->set("line15", "$data[16]");
            $playersb->set("l15", true);
            $playersb->save();
            $this->endscore($sender);
        });
        $form->setTitle(ScoreboardCommand::$sbprefix);
        $form->addLabel("§eBearbeite hier dein Scoreboard.");
        $form->addDropdown("§aTitels", ["§f>>>[§5Cyber§eCraft§f]<<<", "§5Cyber§eCraft", "§f===§f[§5Cyber§eCraft§f]===", "§f[§5Cyber§eCraft§f]"]);
        $form->addInput("§eHier die Zeilen", "Zeile 1");
        $form->addInput("", "Zeile 2");
        $form->addInput("", "Zeile 3");
        $form->addInput("", "Zeile 4");
        $form->addInput("", "Zeile 5");
        $form->addInput("", "Zeile 6");
        $form->addInput("", "Zeile 7");
        $form->addInput("", "Zeile 8");
        $form->addInput("", "Zeile 9");
        $form->addInput("", "Zeile 10");
        $form->addInput("", "Zeile 11");
        $form->addInput("", "Zeile 12");
        $form->addInput("", "Zeile 13");
        $form->addInput("", "Zeile 14");
        $form->addInput("", "Zeile 15");
        $form->sendToPlayer($sender);
        return $form;
    }
}