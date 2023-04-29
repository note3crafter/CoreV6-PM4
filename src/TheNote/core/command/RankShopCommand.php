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

use pocketmine\player\Player;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\formapi\SimpleForm;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use onebone\economyapi\EconomyAPI;

class RankShopCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("rankshop", $api->getSetting("prefix") . "§6Kaufe einen Rang", "/rankshop", ["rshop"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }
        $this->Shop($sender);
        return true;
    }


    public function Shop($player)
    {
        $api = new BaseAPI();
        $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
        $g1 = $groups->getNested("Groups." . $api->getConfig("Rankname1") . ".groupprefix");
        $g2 = $groups->getNested("Groups." . $api->getConfig("Rankname2") . ".groupprefix");
        $g3 = $groups->getNested("Groups." . $api->getConfig("Rankname3") . ".groupprefix");
        $g4 = $groups->getNested("Groups." . $api->getConfig("Rankname4") . ".groupprefix");
        $g5 = $groups->getNested("Groups." . $api->getConfig("Rankname4") . ".groupprefix");
        $g6 = $groups->getNested("Groups." . $api->getConfig("Rankname4") . ".groupprefix");
        $g7 = $groups->getNested("Groups." . $api->getConfig("Rankname4") . ".groupprefix");


        $form = new SimpleForm(function (Player $player, int $data = null) {
            $api = new BaseAPI();
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    $player->sendMessage($api->getSetting("shop") . "§eDanke das du da warst.");
                    break;
                case 1:
                    $this->Rank1($player);
                    break;
                case 2:
                    $this->Rank2($player);
                    break;
                case 3:
                    $this->Rank3($player);
                    break;
                case 4:
                    $this->Rank4($player);
                    break;
                case 5:
                    $this->Rank5($player);
                    break;
                case 6:
                    $this->Rank6($player);
                    break;
                case 7:
                    $this->Rank7($player);
                    break;
            }
        });
        $form->setTitle("§0======§f[§cRangShop§f]§0======");
        $form->addButton("§cVerlassen");
        $form->addButton("Rang :" . $g1 . "\n" . "§0Kostet: " . $api->getConfig("Rankprice1"));
        if ($api->getConfig("Rank2") === true) {
            $form->addButton("Rang :" . $g2 . "\n" . "§0Kostet: " . $api->getConfig("Rankprice2"));
        }
        if ($api->getConfig("Rank3") === true) {
            $form->addButton("Rang :" . $g3 . "\n" . "§0Kostet: " . $api->getConfig("Rankprice3"));
        }
        if ($api->getConfig("Rank4") === true) {
            $form->addButton("Rang :" . $g4 . "\n" . "§0Kostet: " . $api->getConfig("Rankprice4"));
        }
        if ($api->getConfig("Rank4") === true) {
            $form->addButton("Rang :" . $g5 . "\n" . "§0Kostet: " . $api->getConfig("Rankprice5"));
        }
        if ($api->getConfig("Rank5") === true) {
            $form->addButton("Rang :" . $g6 . "\n" . "§0Kostet: " . $api->getConfig("Rankprice6"));
        }
        if ($api->getConfig("Rank6") === true) {
            $form->addButton("Rang :" . $g7 . "\n" . "§0Kostet: " . $api->getConfig("Rankprice7"));
        }
        $form->sendToPlayer($player);
    }

    public function Rank1($player)
    {
        $name = $player->getName();
        $api = new BaseAPI();
        $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
        $playerdata = new Config($this->plugin->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        $m = $api->getMoney($player);
        if ($m >= $api->getConfig("Rankprice1")) {
            $api->removeMoney($player, $api->getConfig("Rankprice1"));
            $groupprefix = $groups->getNested("Groups." . $api->getConfig("Rankname1") . ".groupprefix");
            $playerdata->setNested($name . ".groupprefix", $groupprefix);
            $playerdata->setNested($name . ".group", $api->getConfig("Rankname1"));
            $playerdata->save();
            $playergroup = $playerdata->getNested($name . ".group");
            $nametag = str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playergroup}.nametag"));
            $displayname = str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playerdata->getNested($name.".group")}.displayname"));
            $permissionlist = (array)$groups->getNested("Groups." . $playergroup . ".permissions", []);
            foreach ($permissionlist as $name => $data) {
                $player->addAttachment($this->plugin)->setPermission($data, true);
            }
            $player->setNameTag($nametag);
            $player->setDisplayName($displayname);
            $player->sendMessage($api->getSetting("shop") . "§6Du hast soeben denn Rang §f:§e " . $api->getConfig("Rankname1") . " §6Rang gekauft!");
        } else {
            $player->sendMessage($api->getSetting("error") . "§cDu hast zu wenig Geld um diesen Rang zu kaufen!");
        }
    }

    public function Rank2($player)
    {
        $name = $player->getName();
        $api = new BaseAPI();
        $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
        $playerdata = new Config($this->plugin->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        $m = $api->getMoney($player);
        if ($m >= $api->getConfig("Rankprice2")) {
            $api->removeMoney($player, $api->getConfig("Rankprice2"));
            $groupprefix = $groups->getNested("Groups." . $api->getConfig("Rankname2") . ".groupprefix");
            $playerdata->setNested($name . ".groupprefix", $groupprefix);
            $playerdata->setNested($name . ".group", $api->getConfig("Rankname2"));
            $playerdata->save();
            $playergroup = $playerdata->getNested($name . ".group");
            $nametag = str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playergroup}.nametag"));
            $displayname = str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playerdata->getNested($name.".group")}.displayname"));
            $permissionlist = (array)$groups->getNested("Groups." . $playergroup . ".permissions", []);
            foreach ($permissionlist as $name => $data) {
                $player->addAttachment($this->plugin)->setPermission($data, true);
            }
            $player->setNameTag($nametag);
            $player->setDisplayName($displayname);
            $player->sendMessage($api->getSetting("shop") . "§6Du hast soeben denn Rang §f:§e " . $api->getConfig("Rankname2") . " §6Rang gekauft!");
        } else {
            $player->sendMessage($api->getSetting("error") . "§cDu hast zu wenig Geld um diesen Rang zu kaufen!");
        }
    }

    public function Rank3($player)
    {
        $name = $player->getName();
        $api = new BaseAPI();
        $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
        $playerdata = new Config($this->plugin->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        $m = $api->getMoney($player);
        if ($m >= $api->getConfig("Rankprice3")) {
            $api->removeMoney($player, $api->getConfig("Rankprice3"));
            $groupprefix = $groups->getNested("Groups." . $api->getConfig("Rankname3") . ".groupprefix");
            $playerdata->setNested($name . ".groupprefix", $groupprefix);
            $playerdata->setNested($name . ".group", $api->getConfig("Rankname3"));
            $playerdata->save();
            $playergroup = $playerdata->getNested($name . ".group");
            $nametag = str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playergroup}.nametag"));
            $displayname = str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playerdata->getNested($name.".group")}.displayname"));
            $permissionlist = (array)$groups->getNested("Groups." . $playergroup . ".permissions", []);
            foreach ($permissionlist as $name => $data) {
                $player->addAttachment($this->plugin)->setPermission($data, true);
            }
            $player->setNameTag($nametag);
            $player->setDisplayName($displayname);
            $player->sendMessage($api->getSetting("shop") . "§6Du hast soeben denn Rang §f:§e " . $api->getConfig("Rankname3") . " §6Rang gekauft!");
        } else {
            $player->sendMessage($api->getSetting("error") . "§cDu hast zu wenig Geld um diesen Rang zu kaufen!");
        }
    }

    public function Rank4($player)
    {
        $name = $player->getName();
        $api = new BaseAPI();
        $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
        $playerdata = new Config($this->plugin->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        $m = $api->getMoney($player);
        if ($m >= $api->getConfig("Rankprice4")) {
            $api->removeMoney($player, $api->getConfig("Rankprice4"));
            $groupprefix = $groups->getNested("Groups." . $api->getConfig("Rankname4") . ".groupprefix");
            $playerdata->setNested($name . ".groupprefix", $groupprefix);
            $playerdata->setNested($name . ".group", $api->getConfig("Rankname4"));
            $playerdata->save();
            $playergroup = $playerdata->getNested($name . ".group");
            $nametag = str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playergroup}.nametag"));
            $displayname = str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playerdata->getNested($name.".group")}.displayname"));
            $permissionlist = (array)$groups->getNested("Groups." . $playergroup . ".permissions", []);
            foreach ($permissionlist as $name => $data) {
                $player->addAttachment($this->plugin)->setPermission($data, true);
            }
            $player->setNameTag($nametag);
            $player->setDisplayName($displayname);
            $player->sendMessage($api->getSetting("shop") . "§6Du hast soeben denn Rang §f:§e " . $api->getConfig("Rankname4") . " §6Rang gekauft!");
        } else {
            $player->sendMessage($api->getSetting("error") . "§cDu hast zu wenig Geld um diesen Rang zu kaufen!");
        }
    }

    public function Rank5($player)
    {
        $name = $player->getName();
        $api = new BaseAPI();
        $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
        $playerdata = new Config($this->plugin->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        $m = $api->getMoney($player);
        if ($m >= $api->getConfig("Rankprice5")) {
            $api->removeMoney($player, $api->getConfig("Rankprice5"));
            $groupprefix = $groups->getNested("Groups." . $api->getConfig("Rankname5") . ".groupprefix");
            $playerdata->setNested($name . ".groupprefix", $groupprefix);
            $playerdata->setNested($name . ".group", $api->getConfig("Rankname4"));
            $playerdata->save();
            $playergroup = $playerdata->getNested($name . ".group");
            $nametag = str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playergroup}.nametag"));
            $displayname = str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playerdata->getNested($name.".group")}.displayname"));
            $permissionlist = (array)$groups->getNested("Groups." . $playergroup . ".permissions", []);
            foreach ($permissionlist as $name => $data) {
                $player->addAttachment($this->plugin)->setPermission($data, true);
            }
            $player->setNameTag($nametag);
            $player->setDisplayName($displayname);
            $player->sendMessage($api->getSetting("shop") . "§6Du hast soeben denn Rang §f:§e " . $api->getConfig("Rankname4") . " §6Rang gekauft!");
        } else {
            $player->sendMessage($api->getSetting("error") . "§cDu hast zu wenig Geld um diesen Rang zu kaufen!");
        }
    }

    public function Rank6($player)
    {
        $name = $player->getName();
        $api = new BaseAPI();
        $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
        $playerdata = new Config($this->plugin->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        $m = $api->getMoney($player);
        if ($m >= $api->getConfig("Rankprice6")) {
            $api->removeMoney($player, $api->getConfig("Rankprice6"));
            $groupprefix = $groups->getNested("Groups." . $api->getConfig("Rankname6") . ".groupprefix");
            $playerdata->setNested($name . ".groupprefix", $groupprefix);
            $playerdata->setNested($name . ".group", $api->getConfig("Rankname6"));
            $playerdata->save();
            $playergroup = $playerdata->getNested($name . ".group");
            $nametag = str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playergroup}.nametag"));
            $displayname = str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playerdata->getNested($name.".group")}.displayname"));
            $permissionlist = (array)$groups->getNested("Groups." . $playergroup . ".permissions", []);
            foreach ($permissionlist as $name => $data) {
                $player->addAttachment($this->plugin)->setPermission($data, true);
            }
            $player->setNameTag($nametag);
            $player->setDisplayName($displayname);
            $player->sendMessage($api->getSetting("shop") . "§6Du hast soeben denn Rang §f:§e " . $api->getConfig("Rankname4") . " §6Rang gekauft!");
        } else {
            $player->sendMessage($api->getSetting("error") . "§cDu hast zu wenig Geld um diesen Rang zu kaufen!");
        }
    }

    public function Rank7($player)
    {
        $name = $player->getName();
        $api = new BaseAPI();
        $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
        $playerdata = new Config($this->plugin->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        $m = $api->getMoney($player);
        if ($m >= $api->getConfig("Rankprice7")) {
            $api->removeMoney($player, $api->getConfig("Rankprice7"));
            $groupprefix = $groups->getNested("Groups." . $api->getConfig("Rankname7") . ".groupprefix");
            $playerdata->setNested($name . ".groupprefix", $groupprefix);
            $playerdata->setNested($name . ".group", $api->getConfig("Rankname7"));
            $playerdata->save();
            $playergroup = $playerdata->getNested($name . ".group");
            $nametag = str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playergroup}.nametag"));
            $displayname = str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playerdata->getNested($name.".group")}.displayname"));
            $permissionlist = (array)$groups->getNested("Groups." . $playergroup . ".permissions", []);
            foreach ($permissionlist as $name => $data) {
                $player->addAttachment($this->plugin)->setPermission($data, true);
            }
            $player->setNameTag($nametag);
            $player->setDisplayName($displayname);
            $player->sendMessage($api->getSetting("shop") . "§6Du hast soeben denn Rang §f:§e " . $api->getConfig("Rankname4") . " §6Rang gekauft!");
        } else {
            $player->sendMessage($api->getSetting("error") . "§cDu hast zu wenig Geld um diesen Rang zu kaufen!");
        }
    }
}