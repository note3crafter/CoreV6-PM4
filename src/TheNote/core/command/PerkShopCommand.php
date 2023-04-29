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
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;
use onebone\economyapi\EconomyAPI;
use TheNote\core\formapi\SimpleForm;

class PerkShopCommand extends Command
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("perkshop", $api->getSetting("prefix") . "Kaufe deine Perks", "/perkshop", ["ps"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getSetting("info") . "Du kannst dir diesen Perk im Perkshop kaufen");
            return true;
        }

        $form = new SimpleForm(function (Player $sender, $data) {
            $result = $data;
            $player = $sender->getName();
            $daten = new Config($this->plugin->getDataFolder() . Main::$userfile . $player . ".json", Config::JSON);
            $preise = new Config($this->plugin->getDataFolder() . Main::$setup . "PerkSettings.yml", Config::YAML);
            $api = new BaseAPI();
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    if ($api->getMoney($sender->getName()) < $preise->get("explode")) {
                        $sender->sendMessage($api->getSetting("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return false;
                    }
                    if ($daten->get("explodeperkpermission") === true) {
                        $sender->sendMessage($api->getSetting("error") . "§cDu hast diesen Perk bereits gekauft");
                        return false;
                    } else if ($api->getMoney($sender->getName()) >= $preise->get("explode")) {
                        $api->removeMoney($sender, $preise->get("explode"));
                        $daten->set("explodeperkpermission", true);
                        $daten->set("explodeperk", true);
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
                        $sender->sendMessage($api->getSetting("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }

                    break;
                case 1:
                    if ($api->getMoney($sender->getName()) < $preise->get("angry")) {
                        $sender->sendMessage($api->getSetting("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return false;
                    }
                    if ($daten->get("angryperkpermission") === true) {
                        $sender->sendMessage($api->getSetting("error") . "§cDu hast diesen Perk bereits gekauft");
                        return false;
                    } else if ($api->getMoney($sender->getName()) >= $preise->get("angry")) {
                        $api->removeMoney($sender, $preise->get("angry"));
                        $daten->set("angryperkpermission", true);
                        $daten->set("angryperk", true);
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
                        $sender->sendMessage($api->getSetting("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }

                    break;
                case 2:
                    if ($api->getMoney($sender->getName()) < $preise->get("redstone")) {
                        $sender->sendMessage($api->getSetting("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return false;
                    }
                    if ($daten->get("redstoneperkpermission") === true) {
                        $sender->sendMessage($api->getSetting("error") . "§cDu hast diesen Perk bereits gekauft");
                        return false;
                    } else if ($api->getMoney($sender->getName()) >= $preise->get("redstone")) {
                        $api->removeMoney($sender, $preise->get("redstone"));
                        $daten->set("redstoneperkpermission", true);
                        $daten->set("redstoneperk", true);
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
                        $sender->sendMessage($api->getSetting("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }

                    break;
                case 3:
                    if ($api->getMoney($sender->getName()) < $preise->get("smoke")) {
                        $sender->sendMessage($api->getSetting("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return false;
                    }
                    if ($daten->get("smokeperkpermission") === true) {
                        $sender->sendMessage($api->getSetting("error") . "§cDu hast diesen Perk bereits gekauft");
                        return false;
                    } else if ($api->getMoney($sender->getName()) >= $preise->get("smoke")) {
                        $api->removeMoney($sender, $preise->get("smoke"));
                        $daten->set("smokeperkpermission", true);
                        $daten->set("smokeperk", true);
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
                        $sender->sendMessage($api->getSetting("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }

                    break;
                case 4:
                    if ($api->getMoney($sender->getName()) < $preise->get("lava")) {
                        $sender->sendMessage($api->getSetting("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return false;
                    }
                    if ($daten->get("lavaperkpermission") === true) {
                        $sender->sendMessage($api->getSetting("error") . "§cDu hast diesen Perk bereits gekauft");
                        return false;
                    } else if ($api->getMoney($sender->getName()) >= $preise->get("lava")) {
                        $api->removeMoney($sender, $preise->get("lava"));
                        $daten->set("lavaperkpermission", true);
                        $daten->set("lavaperk", true);
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
                        $sender->sendMessage($api->getSetting("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }

                    break;
                case 5:
                    if ($api->getMoney($sender->getName()) < $preise->get("heart")) {
                        $sender->sendMessage($api->getSetting("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return false;
                    }
                    if ($daten->get("heartperkpermission") === true) {
                        $sender->sendMessage($api->getSetting("error") . "§cDu hast diesen Perk bereits gekauft");
                        return false;
                    } else if ($api->getMoney($sender->getName()) >= $preise->get("heart")) {
                        $api->removeMoney($sender, $preise->get("heart"));
                        $daten->set("heartperkpermission", true);
                        $daten->set("heartperk", true);
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
                        $sender->sendMessage($api->getSetting("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }

                    break;
                case 6:
                    if ($api->getMoney($sender->getName()) < $preise->get("flame")) {
                        $sender->sendMessage($api->getSetting("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return false;
                    }
                    if ($daten->get("flameperkpermission") === true) {
                        $sender->sendMessage($api->getSetting("error") . "§cDu hast diesen Perk bereits gekauft");
                        return false;
                    } else if ($api->getMoney($sender->getName()) >= $preise->get("flame")) {
                        $api->removeMoney($sender, $preise->get("flame"));
                        $daten->set("flameperkpermission", true);
                        $daten->set("flameperk", true);
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
                        $sender->sendMessage($api->getSetting("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }

                    break;
                case 7:
                    if ($api->getMoney($sender->getName()) < $preise->get("portal")) {
                        $sender->sendMessage($api->getSetting("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return false;
                    }
                    if ($daten->get("portalperkpermission") === true) {
                        $sender->sendMessage($api->getSetting("error") . "§cDu hast diesen Perk bereits gekauft");
                        return false;
                    } else if ($api->getMoney($sender->getName()) >= $preise->get("portal")) {
                        $api->removeMoney($sender, $preise->get("portal"));
                        $daten->set("portalperkpermission", true);
                        $daten->set("portalperk", true);
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
                        $sender->sendMessage($api->getSetting("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }

                    break;
                case 8:
                    if ($api->getMoney($sender->getName()) < $preise->get("spore")) {
                        $sender->sendMessage($api->getSetting("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return false;
                    }
                    if ($daten->get("sporeperkpermission") === true) {
                        $sender->sendMessage($api->getSetting("error") . "§cDu hast diesen Perk bereits gekauft");
                        return false;
                    } else if ($api->getMoney($sender->getName()) >= $preise->get("spore")) {
                        $api->removeMoney($sender, $preise->get("spore"));
                        $daten->set("sporeperkpermission", true);
                        $daten->set("sporeperk", true);
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
                        $sender->sendMessage($api->getSetting("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }
                    break;
                case 9:
                    if ($api->getMoney($sender->getName()) < $preise->get("splash")) {
                        $sender->sendMessage($api->getSetting("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return false;
                    }
                    if ($daten->get("splashperkpermission") === true) {
                        $sender->sendMessage($api->getSetting("error") . "§cDu hast diesen Perk bereits gekauft");
                        return false;
                    } else if ($api->getMoney($sender->getName()) >= $preise->get("splash")) {
                        $api->removeMoney($sender, $preise->get("splash"));
                        $daten->set("splashperkpermission", true);
                        $daten->set("splashperk", true);
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
                        $sender->sendMessage($api->getSetting("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }

                    break;
            }
        });
        $player = $sender->getName();
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $perk = new Config($this->plugin->getDataFolder() . Main::$setup . "PerkSettings.yml", Config::YAML);
        $daten = new Config($this->plugin->getDataFolder() . Main::$userfile . $player . ".json", Config::JSON);
        $form->setTitle($api->getSetting("uiname"));
        $form->setContent("§6===========§f[§dPerkShop§f]§6==========\n\n" .
            "§dWillkommen im Perkshop. Hier kannst du verschiedene Perks Kaufen mit IngameGeld die dann dauerhaft aktiviert sind!\n");
        if ($daten->get("explodeperkpermission") === true) {
            $form->addButton("§0ExplodePerk\n§aGekauft", 0);
        } else {
            $form->addButton("§0ExplodePerk\n§cKostet : " . $perk->get("explode"), 0);
        }
        if ($daten->get("angryperkpermission") === true) {
            $form->addButton("§0VillagerAngryPerk\n§aGekauft", 0);
        } else {
            $form->addButton("§0VillagerAngryPerk\n§cKostet : " . $perk->get("angry"), 0);
        }
        if ($daten->get("redstoneperkpermission") === true) {
            $form->addButton("§0RedstonePerk\n§aGekauft", 0);
        } else {
            $form->addButton("§0RedstonePerk\n§cKostet : " . $perk->get("redstone"), 0);
        }
        if ($daten->get("smokeperkpermission") === true) {
            $form->addButton("§0RauchPerk\n§aGekauft", 0);
        } else {
            $form->addButton("§0RauchPerk\n§cKostet : " . $perk->get("smoke"), 0);
        }
        if ($daten->get("lavaperkpermission") === true) {
            $form->addButton("§0LavaPerk\n§aGekauft", 0);
        } else {
            $form->addButton("§0LavaPerk\n§cKostet : " . $perk->get("lava"), 0);
        }
        if ($daten->get("heartperkpermission") === true) {
            $form->addButton("§0HerzPerk\n§aGekauft", 0);
        } else {
            $form->addButton("§0HerzPerk\n§cKostet : " . $perk->get("heart"), 0);
        }
        if ($daten->get("flameperkpermission") === true) {
            $form->addButton("§0FlammenPerk\n§aGekauft", 0);
        } else {
            $form->addButton("§0FlammenPerk\n§cKostet : " . $perk->get("flame"), 0);
        }
        if ($daten->get("portalperkpermission") === true) {
            $form->addButton("§0PortalPerk\n§aGekauft", 0);
        } else {
            $form->addButton("§0PortalPerk\n§cKostet : " . $perk->get("portal"), 0);
        }
        if ($daten->get("sporeperkpermission") === true) {
            $form->addButton("§0SporenPerk\n§aGekauft", 0);
        } else {
            $form->addButton("§0SporenPerk\n§cKostet : " . $perk->get("spore"), 0);
        }
        if ($daten->get("splashperkpermission") === true) {
            $form->addButton("§0WasserPerk\n§aGekauft", 0);
        } else {
            $form->addButton("§0WasserPerk\n§cKostet : " . $perk->get("splash"), 0);
        }
        $form->sendToPlayer($sender);
        return true;
    }
}