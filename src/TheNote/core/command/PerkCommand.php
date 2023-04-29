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

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;
use TheNote\core\formapi\SimpleForm;

class PerkCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("perk", $api->getSetting("prefix") . "Wähle dein §dPerk §6aus.", "/perk");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }
        $form = new SimpleForm(function (Player $sender, $data) {
            $result = $data;
            $player = $sender->getName();
            $daten = new Config($this->plugin->getDataFolder() . Main::$userfile . $player . ".json", Config::JSON);
            $api = new BaseAPI();
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    if ($daten->get("explodeperk") === null) {
                        $this->noPerk($sender);
                    } elseif ($daten->get("explodeperkpermission") === false or null) {
                        $this->noPerk($sender);
                    } elseif ($daten->get("explodeperkpermission") === true) {
                        if ($daten->get("explode") === false) {
                            $daten->set("explode", true);
                            $daten->set("angry", false);
                            $daten->set("redstone", false);
                            $daten->set("smoke", false);
                            $daten->set("lava", false);
                            $daten->set("heart", false);
                            $daten->set("flame", false);
                            $daten->set("portal", false);
                            $daten->set("spore", false);
                            $daten->set("splash", false);
                            $daten->save();
                            $sender->sendMessage($api->getSetting("perks") . "Du hast dein Explosionsperk Aktiviert!");
                        } else if ($daten->get("explode") === true) {
                            $daten->set("explode", false);
                            $daten->set("angry", false);
                            $daten->set("redstone", false);
                            $daten->set("smoke", false);
                            $daten->set("lava", false);
                            $daten->set("heart", false);
                            $daten->set("flame", false);
                            $daten->set("portal", false);
                            $daten->set("spore", false);
                            $daten->set("splash", false);
                            $daten->save();
                            $sender->sendMessage($api->getSetting("perks") . "Du hast dein Explosionsperk Deaktiviert!");
                        }
                    }
                    break;
                case 1:
                    if ($daten->get("angryperk") === null) {
                        $this->noPerk($sender);
                    } elseif ($daten->get("angryperkpermission") === false or null) {
                        $this->noPerk($sender);
                    } elseif ($daten->get("angryperkpermission") === true) {
                        if ($daten->get("angry") === false) {
                            $daten->set("explode", false);
                            $daten->set("angry", true);
                            $daten->set("redstone", false);
                            $daten->set("smoke", false);
                            $daten->set("lava", false);
                            $daten->set("heart", false);
                            $daten->set("flame", false);
                            $daten->set("portal", false);
                            $daten->set("spore", false);
                            $daten->set("splash", false);
                            $daten->save();
                            $sender->sendMessage($api->getSetting("perks") . "Du hast dein Angryperk Aktiviert!");
                        } else if ($daten->get("angry") === true) {
                            $daten->set("explode", false);
                            $daten->set("angry", false);
                            $daten->set("redstone", false);
                            $daten->set("smoke", false);
                            $daten->set("lava", false);
                            $daten->set("heart", false);
                            $daten->set("flame", false);
                            $daten->set("portal", false);
                            $daten->set("spore", false);
                            $daten->set("splash", false);
                            $daten->save();
                            $sender->sendMessage($api->getSetting("perks") . "Du hast dein Angryperk Deaktiviert!");
                        }
                    }
                    break;
                case 2:
                    if ($daten->get("redstoneperk") === null) {
                        $this->noPerk($sender);
                    } elseif ($daten->get("redstoneperkpermission") === false or null) {
                        $this->noPerk($sender);
                    } elseif ($daten->get("redstoneperkpermission") === true) {
                        if ($daten->get("redstone") === false) {
                            $daten->set("explode", false);
                            $daten->set("angry", false);
                            $daten->set("redstone", true);
                            $daten->set("smoke", false);
                            $daten->set("lava", false);
                            $daten->set("heart", false);
                            $daten->set("flame", false);
                            $daten->set("portal", false);
                            $daten->set("spore", false);
                            $daten->set("splash", false);
                            $daten->save();
                            $sender->sendMessage($api->getSetting("perks") . "Du hast dein Redstoneperk Aktiviert!");
                        } else if ($daten->get("redstone") === true) {
                            $daten->set("explode", false);
                            $daten->set("angry", false);
                            $daten->set("redstone", false);
                            $daten->set("smoke", false);
                            $daten->set("lava", false);
                            $daten->set("heart", false);
                            $daten->set("flame", false);
                            $daten->set("portal", false);
                            $daten->set("spore", false);
                            $daten->set("splash", false);
                            $daten->save();
                            $sender->sendMessage($api->getSetting("perks") . "Du hast dein Redstoneperk Deaktiviert!");
                        }
                    }
                    break;
                case 3:
                    if ($daten->get("smokeperk") === null) {
                        $this->noPerk($sender);
                    } elseif ($daten->get("smokeperkpermission") === false or null) {
                        $this->noPerk($sender);
                    } elseif ($daten->get("smokeperkpermission") === true) {
                        if ($daten->get("smoke") === false) {
                            $daten->set("explode", false);
                            $daten->set("angry", false);
                            $daten->set("redstone", false);
                            $daten->set("smoke", true);
                            $daten->set("lava", false);
                            $daten->set("heart", false);
                            $daten->set("flame", false);
                            $daten->set("portal", false);
                            $daten->set("spore", false);
                            $daten->set("splash", false);
                            $daten->save();
                            $sender->sendMessage($api->getSetting("perks") . "Du hast dein Smokeperk Aktiviert!");
                        } else if ($daten->get("smoke") === true) {
                            $daten->set("explode", false);
                            $daten->set("angry", false);
                            $daten->set("redstone", false);
                            $daten->set("smoke", false);
                            $daten->set("lava", false);
                            $daten->set("heart", false);
                            $daten->set("flame", false);
                            $daten->set("portal", false);
                            $daten->set("spore", false);
                            $daten->set("splash", false);
                            $daten->save();
                            $sender->sendMessage($api->getSetting("perks") . "Du hast dein Smokeperk Deaktiviert!");
                        }
                    }
                    break;
                case 4:
                    if ($daten->get("lavaperk") === null) {
                        $this->noPerk($sender);
                    } elseif ($daten->get("lavaperkpermission") === false or null) {
                        $this->noPerk($sender);
                    } elseif ($daten->get("lavaperkpermission") === true) {
                        if ($daten->get("lava") === false) {
                            $daten->set("explode", false);
                            $daten->set("angry", false);
                            $daten->set("redstone", false);
                            $daten->set("smoke", false);
                            $daten->set("lava", true);
                            $daten->set("heart", false);
                            $daten->set("flame", false);
                            $daten->set("portal", false);
                            $daten->set("spore", false);
                            $daten->set("splash", false);
                            $daten->save();
                            $sender->sendMessage($api->getSetting("perks") . "Du hast dein Lavaperk Aktiviert!");
                        } else if ($daten->get("lava") === true) {
                            $daten->set("explode", false);
                            $daten->set("angry", false);
                            $daten->set("redstone", false);
                            $daten->set("smoke", false);
                            $daten->set("lava", false);
                            $daten->set("heart", false);
                            $daten->set("flame", false);
                            $daten->set("portal", false);
                            $daten->set("spore", false);
                            $daten->set("splash", false);
                            $daten->save();
                            $sender->sendMessage($api->getSetting("perks") . "Du hast dein Lavaperk Deaktiviert!");
                        }
                    }
                    break;
                case 5:
                    if ($daten->get("heartperk") === null) {
                        $this->noPerk($sender);
                    } elseif ($daten->get("heartperkpermission") === false or null) {
                        $this->noPerk($sender);
                    } elseif ($daten->get("heartperkpermission") === true) {
                        if ($daten->get("heart") === false) {
                            $daten->set("explode", false);
                            $daten->set("angry", false);
                            $daten->set("redstone", false);
                            $daten->set("smoke", false);
                            $daten->set("lava", false);
                            $daten->set("heart", true);
                            $daten->set("flame", false);
                            $daten->set("portal", false);
                            $daten->set("spore", false);
                            $daten->set("splash", false);
                            $daten->save();
                            $sender->sendMessage($api->getSetting("perks") . "Du hast dein Herzperk Aktiviert!");
                        } else if ($daten->get("heart") === true) {
                            $daten->set("explode", false);
                            $daten->set("angry", false);
                            $daten->set("redstone", false);
                            $daten->set("smoke", false);
                            $daten->set("lava", false);
                            $daten->set("heart", false);
                            $daten->set("flame", false);
                            $daten->set("portal", false);
                            $daten->set("spore", false);
                            $daten->set("splash", false);
                            $daten->save();
                            $sender->sendMessage($api->getSetting("perks") . "Du hast dein Herzperk Deaktiviert!");
                        }
                    }
                    break;
                case 6:
                    if ($daten->get("flameperk") === null) {
                        $this->noPerk($sender);
                    } elseif ($daten->get("flameperkpermission") === false or null) {
                        $this->noPerk($sender);
                    } elseif ($daten->get("flameperkpermission") === true) {
                        if ($daten->get("flame") === false) {
                            $daten->set("explode", false);
                            $daten->set("angry", false);
                            $daten->set("redstone", false);
                            $daten->set("smoke", false);
                            $daten->set("lava", false);
                            $daten->set("heart", false);
                            $daten->set("flame", true);
                            $daten->set("portal", false);
                            $daten->set("spore", false);
                            $daten->set("splash", false);
                            $daten->save();
                            $sender->sendMessage($api->getSetting("perks") . "Du hast dein Flamesperk Aktiviert!");
                        } else if ($daten->get("flame") === true) {
                            $daten->set("explode", false);
                            $daten->set("angry", false);
                            $daten->set("redstone", false);
                            $daten->set("smoke", false);
                            $daten->set("lava", false);
                            $daten->set("heart", false);
                            $daten->set("flame", false);
                            $daten->set("portal", false);
                            $daten->set("spore", false);
                            $daten->set("splash", false);
                            $daten->save();
                            $sender->sendMessage($api->getSetting("perks") . "Du hast dein Flameperk Deaktiviert!");
                        }
                    }
                    break;
                case 7:
                    if ($daten->get("portalperk") === null) {
                        $this->noPerk($sender);
                    } elseif ($daten->get("portalperkpermission") === false or null) {
                        $this->noPerk($sender);
                    } elseif ($daten->get("portalperkpermission") === true) {
                        if ($daten->get("portal") === false) {
                            $daten->set("explode", false);
                            $daten->set("angry", false);
                            $daten->set("redstone", false);
                            $daten->set("smoke", false);
                            $daten->set("lava", false);
                            $daten->set("heart", false);
                            $daten->set("flame", false);
                            $daten->set("portal", true);
                            $daten->set("spore", false);
                            $daten->set("splash", false);
                            $daten->save();
                            $sender->sendMessage($api->getSetting("perks") . "Du hast dein Portalperk Aktiviert!");
                        } else if ($daten->get("portal") === true) {
                            $daten->set("explode", false);
                            $daten->set("angry", false);
                            $daten->set("redstone", false);
                            $daten->set("smoke", false);
                            $daten->set("lava", false);
                            $daten->set("heart", false);
                            $daten->set("flame", false);
                            $daten->set("portal", false);
                            $daten->set("spore", false);
                            $daten->set("splash", false);
                            $daten->save();
                            $sender->sendMessage($api->getSetting("perks") . "Du hast dein Portalperk Deaktiviert!");
                        }
                    }
                    break;
                case 8:
                    if ($daten->get("sporeperk") === null) {
                        $this->noPerk($sender);
                    } elseif ($daten->get("sporeperkpermission") === false or null) {
                        $this->noPerk($sender);
                    } elseif ($daten->get("sporeperkpermission") === true) {
                        if ($daten->get("spore") === false) {
                            $daten->set("explode", false);
                            $daten->set("angry", false);
                            $daten->set("redstone", false);
                            $daten->set("smoke", false);
                            $daten->set("lava", false);
                            $daten->set("heart", false);
                            $daten->set("flame", false);
                            $daten->set("portal", false);
                            $daten->set("spore", true);
                            $daten->set("splash", false);
                            $daten->save();
                            $sender->sendMessage($api->getSetting("perks") . "Du hast dein Sporeperk Aktiviert!");
                        } else if ($daten->get("spore") === true) {
                            $daten->set("explode", false);
                            $daten->set("angry", false);
                            $daten->set("redstone", false);
                            $daten->set("smoke", false);
                            $daten->set("lava", false);
                            $daten->set("heart", false);
                            $daten->set("flame", false);
                            $daten->set("portal", false);
                            $daten->set("spore", false);
                            $daten->set("splash", false);
                            $daten->save();
                            $sender->sendMessage($api->getSetting("perks") . "Du hast dein Sporeperk Deaktiviert!");
                        }
                    }
                    break;
                case 9:
                    if ($daten->get("splashperk") === null) {
                        $this->noPerk($sender);
                    } elseif ($daten->get("splashperkpermission") === false or null) {
                        $this->noPerk($sender);
                    } elseif ($daten->get("splashperkpermission") === true) {
                        if ($daten->get("splash") === false) {
                            $daten->set("explode", false);
                            $daten->set("angry", false);
                            $daten->set("redstone", false);
                            $daten->set("smoke", false);
                            $daten->set("lava", false);
                            $daten->set("heart", false);
                            $daten->set("flame", false);
                            $daten->set("portal", false);
                            $daten->set("spore", false);
                            $daten->set("splash", true);
                            $daten->save();
                            $sender->sendMessage($api->getSetting("perks") . "Du hast dein Splashperk Aktiviert!");
                        } else if ($daten->get("splash") === true) {
                            $daten->set("explode", false);
                            $daten->set("angry", false);
                            $daten->set("redstone", false);
                            $daten->set("smoke", false);
                            $daten->set("lava", false);
                            $daten->set("heart", false);
                            $daten->set("flame", false);
                            $daten->set("portal", false);
                            $daten->set("spore", false);
                            $daten->set("splash", false);
                            $daten->save();
                            $sender->sendMessage($api->getSetting("perks") . "Du hast dein Splashperk Deaktiviert!");
                        }
                    }
                    break;
            }
        });
        $player = $sender->getName();
        $daten = new Config($this->plugin->getDataFolder() . Main::$userfile . $player . ".json", Config::JSON);
        $perk = new Config($this->plugin->getDataFolder() . Main::$setup . "PerkSettings.yml", Config::YAML);

        $form->setTitle($api->getSetting("uiname"));
        $form->setContent("§6=============§f[§dPerks§f]§6============\n\n" .
            "§aAktiviere §6oder §cDeaktiviere §6dein Perk");
        if ($daten->get("explodeperkpermission") === false) {
            $form->addButton("§0ExplodePerk\n§cKostet : " . $perk->get("explode"), 0);
        } elseif ($daten->get("explode") === false) {
            $form->addButton("§0ExplodePerk\n§cDeaktiviert");
        } elseif ($daten->get("explode") === true) {
            $form->addButton("§0ExplodePerk\n§aAktiviert", 0);
        }

        if ($daten->get("angryperkpermission") === false) {
            $form->addButton("§0VillagerAngryPerk\n§cKostet : " . $perk->get("angry"), 0);
        } elseif ($daten->get("angry") === false) {
            $form->addButton("§0AngryPerk\n§cDeaktiviert");
        } elseif ($daten->get("angry") === true) {
            $form->addButton("§0AngryPerk\n§aAktiviert", 0);
        }

        if ($daten->get("redstoneperkpermission") === false) {
            $form->addButton("§0RedstonePerk\n§cKostet : " . $perk->get("redstone"), 0);
        } elseif ($daten->get("redstone") === false) {
            $form->addButton("§0RedstonePerk\n§cDeaktiviert");
        } elseif ($daten->get("redstone") === true) {
            $form->addButton("§0RedstonePerk\n§aAktiviert", 0);
        }
        if ($daten->get("smokeperkpermission") === false) {
            $form->addButton("§0RauchPerk\n§cKostet : " . $perk->get("smoke"), 0);
        } elseif ($daten->get("smoke") === false) {
            $form->addButton("§0RauchPerk\n§cDeaktiviert");
        } elseif ($daten->get("smoke") === true) {
            $form->addButton("§0RauchPerk\n§aAktiviert", 0);
        }

        if ($daten->get("lavaperkpermission") === false) {
            $form->addButton("§0LavaPerk\n§cKostet : " . $perk->get("lava"), 0);
        } elseif ($daten->get("lava") === false) {
            $form->addButton("§0LavaPerk\n§cDeaktiviert");
        } elseif ($daten->get("lava") === true) {
            $form->addButton("§0LavaPerk\n§aAktiviert", 0);
        }

        if ($daten->get("heartperkpermission") === false) {
            $form->addButton("§0HerzPerk\n§cKostet : " . $perk->get("heart"), 0);
        } elseif ($daten->get("heart") === false) {
            $form->addButton("§0HerzPerk\n§cDeaktiviert");
        } elseif ($daten->get("heart") === true) {
            $form->addButton("§0HerzPerk\n§aAktiviert", 0);
        }

        if ($daten->get("flameperkpermission") === false) {
            $form->addButton("§0FlammenPerk\n§cKostet : " . $perk->get("flame"), 0);
        } elseif ($daten->get("flame") === false) {
            $form->addButton("§0FlammenPerk\n§cDeaktiviert");
        } elseif ($daten->get("flame") === true) {
            $form->addButton("§0FlammenPerk\n§aAktiviert", 0);
        }

        if ($daten->get("portalperkpermission") === false) {
            $form->addButton("§0PortalPerk\n§cKostet : " . $perk->get("portal"), 0);
        } elseif ($daten->get("portal") === false) {
            $form->addButton("§0PortalPerk\n§cDeaktiviert");
        } elseif ($daten->get("portal") === true) {
            $form->addButton("§0PortalPerk\n§aAktiviert", 0);
        }
        if ($daten->get("sporeperkpermission") === false) {
            $form->addButton("§0SporenPerk\n§cKostet : " . $perk->get("spore"), 0);
        } elseif ($daten->get("spore") === false) {
            $form->addButton("§0SporenPerk\n§cDeaktiviert");
        } elseif ($daten->get("spore") === true) {
            $form->addButton("§0SporenPerk\n§aAktiviert", 0);
        }

        if ($daten->get("splashperkpermission") === false) {
            $form->addButton("§0WasserPerk\n§cKostet : " . $perk->get("splash"), 0);
        } elseif ($daten->get("splash") === false) {
            $form->addButton("§0WasserPerk\n§cDeaktiviert");
        } elseif ($daten->get("splash") === true) {
            $form->addButton("§0WasserPerk\n§aAktiviert", 0);
        }
        $form->sendToPlayer($sender);
        return true;
    }

    public function noPerk($sender): bool
    {
        $api = new BaseAPI();
        $form = new SimpleForm(function (Player $sender, int $data = null) {

            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    Server::getInstance()->dispatchCommand($sender, "perkshop");
                    break;
            }

        });
        $form->setTitle($api->getSetting("uiname"));
        $form->setContent("§6============§f[§dPerkShop§f]§6===========\n\n" .
            "§cDu hast diesen Perk noch nicht gekauft! Drücke auf PerkShop um dir deine Perks zu kaufen! ");
        $form->addButton("§0PerkShop");
        $form->addButton("§0Abbrechen");
        $form->sendToPlayer($sender);
        return true;
    }
}