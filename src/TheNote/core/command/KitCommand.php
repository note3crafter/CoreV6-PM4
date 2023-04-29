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

use DateTimeZone;
use Exception;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use TheNote\core\BaseAPI;
use TheNote\core\Main;
use TheNote\core\formapi\SimpleForm;
use onebone\economyapi\EconomyAPI;
use pocketmine\utils\Config;
use DateTime;

class KitCommand extends Command
{

    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("kit", $api->getSetting("prefix") . "Wähle dein Kit", "/kit");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }
        $form = new SimpleForm(function (Player $sender, $data) {
            $name = $sender->getName();
            $mymoney = $this->plugin->getServer()->getPluginManager()->getPlugin("EconomyAPI");
            $user = new Config($this->plugin->getDataFolder() . Main::$userfile . $name . ".json", Config::JSON);
            $result = $data;
            if ($result === null) {
                return true;
            }
            $if = ItemFactory::getInstance();
            switch ($result) {
                case 0: #+7 day
                    $api = new BaseAPI();
                    $kit = new Config($this->plugin->getDataFolder() . Main::$setup . "kitsettings.yml", Config::YAML);
                    $money = new Config($this->plugin->getDataFolder() . Main::$cloud . "Money.yml", Config::YAML);
                    $bannedtime = $user->get("weekcrate");
                    $time = new DateTime("$bannedtime", new DateTimeZone("Europe/Berlin"));
                    $now = new DateTime("now", new DateTimeZone("Europe/Berlin"));
                    $inv = $sender->getInventory();
                    $emptySlots = $inv->getSize() - count($inv->getContents());

                    if ($time->format("d.m.Y H:i") > $now->format("d.m.Y H:i")) {
                        $sender->sendMessage($api->getSetting("kits") . "§r§6Du kannst deine Wöchentliche Belohnung erst am§c $bannedtime h §6wieder abholen.");
                        return false;
                    } elseif ($sender->getInventory()->slotExists($emptySlots)) {
                        $sender->sendMessage($api->getSetting("kits") . "§cLeere dein Inventar vollständig bevor du dieses Kit beanspruchen kannst!");
                        return false;
                    } else {
                        $newtime = new DateTime("now", new DateTimeZone("Europe/Berlin"));
                        $newtime->modify("+7 day");
                        $user->set("weekcrate", $newtime->format("d.m.Y H:i"));
                        $user->save();
                        if ($this->plugin->economyapi === null) {
                            $old = $money->getNested("money." . $sender->getName());
                            $money->setNested("money." . $sender->getName(), $old + $kit->get("moneyweekly"));
                            $money->save();
                        } else {
                            $mymoney->addMoney($sender, $kit->get("moneyweekly"));
                        }
                        $user->set("coins", $user->get("coins") + 200);
                        $user->save();
                        foreach ($kit->get("SlotsWeekly", []) as $item) {
                            $result = ItemFactory::getInstance()->get($item["id"], $item["damage"], $item["count"]);
                            $result->setCustomName($item["name"]);
                            $result->setLore([$item["lore"]]);
                            $sender->getInventory()->setItem($item["slot"], $result);
                        }
                        $sender->sendMessage($api->getSetting("kits") . "Du hast dein Wöchentliches Kit erhalten sowie " . $kit->get("moneyweekly") . "$ sowie " . $kit->get("coinsweekly") . " Coins Bekommen!");
                    }
                    break;
                case 1: #+1 day
                    $api = new BaseAPI();
                    $kit = new Config($this->plugin->getDataFolder() . Main::$setup . "kitsettings.yml", Config::YAML);
                    $money = new Config($this->plugin->getDataFolder() . Main::$cloud . "Money.yml", Config::YAML);
                    $name = $sender->getName();
                    $inv = $sender->getInventory();
                    $emptySlots = $inv->getSize() - count($inv->getContents());
                    $bannedtime = $user->get("dailycrate");
                    $time = new DateTime("$bannedtime", new DateTimeZone("Europe/Berlin"));
                    $now = new DateTime("now", new DateTimeZone("Europe/Berlin"));

                    if ($time->format("d.m.Y H:i") > $now->format("d.m.Y H:i")) {
                        $sender->sendMessage($api->getSetting("kits") . "§r§6Du kannst deine Tägliche Belohnung erst morgen den§c $bannedtime h §6wieder abholen.");
                        return false;
                    } elseif (!$sender->getInventory()->slotExists($emptySlots)) {
                        $sender->sendMessage($api->getSetting("kits") . "§cLeere dein Inventar vollständig bevor du dieses Kit beanspruchen kannst!");
                        return false;
                    } else {
                        $newtime = new DateTime("now", new DateTimeZone("Europe/Berlin"));
                        $newtime->modify("+1 day");
                        $user->set("dailycrate", $newtime->format("d.m.Y H:i"));
                        $user->save();
                        $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), 'key Daily ' . $name . ' 1');
                        if ($this->plugin->economyapi === null) {
                            $old = $money->getNested("money." . $sender->getName());
                            $money->setNested("money." . $sender->getName(), $old + $kit->get("moneydaily"));
                            $money->save();
                        } else {
                            $mymoney->addMoney($sender, $kit->get("moneydaily"));
                        }
                        $user->set("coins", $user->get("coins") + $kit->get("coinsdaily"));
                        $user->save();
                        $sender->getInventory()->setItem($if->get($kit->get("item1d")));
                        $sender->getInventory()->setItem($if->get($kit->get("item2d")));
                        $sender->getInventory()->setItem($if->get($kit->get("item3d")));
                        $sender->getInventory()->setItem($if->get($kit->get("item4d")));
                        $sender->getInventory()->setItem($if->get($kit->get("item5d")));
                        $sender->getInventory()->setItem($if->get($kit->get("item6d")));
                        $sender->getInventory()->setItem($if->get($kit->get("item7d")));
                        $sender->getInventory()->setItem($if->get($kit->get("item8d")));
                        $sender->getInventory()->setItem($if->get($kit->get("item9d")));
                        $sender->sendMessage($api->getSetting("kits") . "Du hast dein Tägliches Kit erhalten sowie " . $kit->get("moneydaily") . "$, " . $kit->get("coinsdaily") . " Coins und 1 Dailykey!");
                    }
                    break;
                case 2: #+ 1 hour
                    $api = new BaseAPI();
                    $kit = new Config($this->plugin->getDataFolder() . Main::$setup . "kitsettings.yml", Config::YAML);
                    $money = new Config($this->plugin->getDataFolder() . Main::$cloud . "Money.yml", Config::YAML);
                    $name = $sender->getName();
                    $inv = $sender->getInventory();
                    $emptySlots = $inv->getSize() - count($inv->getContents());
                    $bannedtime = $user->get("hourcrate");
                    $time = new DateTime("$bannedtime", new DateTimeZone("Europe/Berlin"));
                    $now = new DateTime("now", new DateTimeZone("Europe/Berlin"));

                    if ($time->format("d.m.Y H:i") > $now->format("d.m.Y H:i")) {
                        $sender->sendMessage($api->getSetting("kits") . "§r§6Du kannst deine Stündliche Belohnung erst um§c $bannedtime h §6wieder abholen.");
                        return false;
                    } else if (!$sender->getInventory()->slotExists($emptySlots)) {
                        $sender->sendMessage($api->getSetting("kits") . "§cLeere dein Inventar vollständig bevor du dieses Kit beanspruchen kannst!");
                        return false;
                    } else {
                        $newtime = new DateTime("now", new DateTimeZone("Europe/Berlin"));
                        $newtime->modify("+ 1 hour");
                        $user->set("hourcrate", $newtime->format("d.m.Y H:i"));
                        $user->save();
                        $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), 'key Stundlicher ' . $name . ' 1');
                        if ($this->plugin->economyapi === null) {
                            $old = $money->getNested("money." . $sender->getName());
                            $money->setNested("money." . $sender->getName(), $old + $kit->get("moneyhour"));
                            $money->save();
                        } else {
                            $mymoney->addMoney($sender, $kit->get("moneyhour"));
                        }
                        $sender->getInventory()->setItem($if->get($kit->get("item1h")));
                        $sender->getInventory()->setItem($if->get($kit->get("item2h")));
                        $sender->getInventory()->setItem($if->get($kit->get("item3h")));
                        $sender->getInventory()->setItem($if->get($kit->get("item4h")));
                        $sender->getInventory()->setItem($if->get($kit->get("item5h")));
                        $sender->getInventory()->setItem($if->get($kit->get("item6h")));
                        $sender->getInventory()->setItem($if->get($kit->get("item7h")));
                        $sender->g()->setItem($if->get($kit->get("item8h")));
                        $sender->getInventory()->setItem($if->get($kit->get("item9h")));
                        $sender->sendMessage($api->getSetting("kits") . "Du hast dein Stündliches Kit erhalten sowie " . $kit->get("moneyhour") . "$ und 1 Stündlicher Key!");
                    }
                    break;
            }
        });
        $name = $sender->getName();
        $user = new Config($this->plugin->getDataFolder() . Main::$userfile . $name . ".json", Config::JSON);
        try {
            $now = new DateTime("now", new DateTimeZone("Europe/Berlin"));
        } catch (Exception $e) {
        }

        $week = $user->get("weekcrate");
        $day = $user->get("dailycrate");
        $hour = $user->get("hourcrate");

        try {
            $timew = new DateTime("$week", new DateTimeZone("Europe/Berlin"));
        } catch (Exception $e) {
        }
        try {
            $timed = new DateTime("$day", new DateTimeZone("Europe/Berlin"));
        } catch (Exception $e) {
        }
        try {
            $timeh = new DateTime("$hour", new DateTimeZone("Europe/Berlin"));
        } catch (Exception $e) {
        }

        $form->setTitle($api->getSetting("uiname"));
        $form->setContent("§eWähle dein Kit");


        if ($timew->format("d.m.Y H:i") > $now->format("d.m.Y H:i")) {
            $form->addButton("§0Wöchentliches Kit\n§r§cAbholbar am : $week");
        } else {
            $form->addButton("§0Wöchentliches Kit");
        }

        if ($timed->format("d.m.Y H:i") > $now->format("d.m.Y H:i")) {
            $form->addButton("§0Tägliches Kit\n§r§cAbholbar am : $day");
        } else {
            $form->addButton("§0Tägliches Kit");
        }


        if ($timeh->format("d.m.Y H:i") > $now->format("d.m.Y H:i")) {
            $form->addButton("§0Stündliches Kit\n§r§cAbholbar um: $hour");
        } else {
            $form->addButton("§0Stündliches Kit");
        }

        $form->sendToPlayer($sender);
        return true;
    }
}