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
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class GruppeCommand extends Command
{
    public $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("group", $api->getSetting("prefix") . $api->getLang("groupprefix"), "/group", ["gruppe"]);
        $this->setPermission("core.command.group");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
        $playerdata = new Config($this->plugin->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        $api = new BaseAPI();
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("nopermission"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage("§f=========== " . $api->getSetting("gruppe") . "§f===========");
            $sender->sendMessage("§6/group add {groupname}");
            $sender->sendMessage("§6/group list");
            $sender->sendMessage("§6/group remove {groupname}");
            $sender->sendMessage("§6/group addperm {groupname} {permission}");
            $sender->sendMessage("§6/group removeperm {groupname} {permission}");
            $sender->sendMessage("§6/group default {groupname}");
            $sender->sendMessage("§6/group set {player} {groupname}");
            $sender->sendMessage("§6/group adduserperm {player} {permission}");
            $sender->sendMessage("§6/group removeuserperm {player} {permission}");
            $sender->sendMessage("§6/group listgroupperm {groupname}");
            $sender->sendMessage("§6/group listuserperm {groupname}");
            return false;
        }
        if ($sender->hasPermission("core.command.group")) {
            if ($args[0] == "add") {
                if (empty($args[1])) {
                    $sender->sendMessage($api->getSetting("info") . "Use : /group add {groupname}");
                    return false;
                }
                $groupName = $args[1];
                if ($groups->getNested("Groups." . $groupName) !== null) {
                    $sender->sendMessage($api->getSetting("error") . "Die Gruppe gibt es Bereits!");
                    return false;
                }
                $groups->setNested("Groups." . $groupName . ".groupprefix", $groupName);
                $groups->setNested("Groups." . $groupName . ".format1", "[$groupName] : {name} | {msg}");
                $groups->setNested("Groups." . $groupName . ".format2", "[$groupName] : {clan} {name} | {msg}");
                $groups->setNested("Groups." . $groupName . ".format3", "[$groupName] : {heirat} {name} | {msg}");
                $groups->setNested("Groups." . $groupName . ".format4", "[$groupName] : {heirat} {clan} {name} | {msg}");
                $groups->setNested("Groups." . $groupName . ".nametag", "$groupName §7: §8{name}");
                $groups->setNested("Groups." . $groupName . ".displayname", "$groupName §7: §8{name}");
                $groups->setNested("Groups." . $groupName . ".permissions", ["CoreV5"]);
                $groups->save();
                $message = str_replace("{group}" , $groupName, $api->getLang("groupaddsucces"));
                $sender->sendMessage($api->getSetting("gruppe") . $message);
            }
            if ($args[0] == "list") {
                $list = [];
                $grouplist = $groups->get("Groups");
                foreach ($grouplist as $name => $data) $list[] = $name;
                $sender->sendMessage($api->getSetting("gruppe") . "\n§8- §7" . implode("\n§8-§7 ", $list));
            }
            if ($args[0] == "remove") {
                if (empty($args[1])) {
                    $sender->sendMessage($api->getSetting("info") ."Use : /group remove {groupname}");
                    return false;
                }
                $groupName = $args[1];
                if ($groups->getNested("Groups." . $groupName) == null) {
                    $sender->sendMessage($api->getSetting("error") . $api->getLang("grouperror"));
                    return true;
                }
                $groups->removeNested("Groups." . $groupName);
                $groups->save();
                $message = str_replace("{group}" , $groupName, $api->getLang("groupremovesucces"));
                $sender->sendMessage($api->getSetting("gruppe") . $message);
            }
            if ($args[0] == "addperm") {
                if (empty($args[1])) {
                    $sender->sendMessage($api->getSetting("info") ."Use : /group addperm {groupname}");
                    return false;
                }
                $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
                $groupName = $args[1];
                if ($groups->getNested("Groups." . $groupName) == null) {
                    $sender->sendMessage($api->getSetting("error") . $api->getLang("grouperrorperms"));
                    return true;
                }
                $perms = $groups->getNested("Groups.{$groupName}.permissions", []);
                $permission = $args[2];
                $perms[] = $permission;
                $groups->setNested("Groups.{$groupName}.permissions", $perms);
                $groups->save();
                $message = str_replace("{group}" , $args[1], $api->getLang("groupaddpermsucces"));
                $message1 = str_replace("{perm}" , $args[2], $message);
                $sender->sendMessage($api->getSetting("gruppe") . $message1);
            }
            if ($args[0] == "removeperm") {
                if (empty($args[1])) {
                    $sender->sendMessage($api->getSetting("info") ."Use : /group removeperm {groupname}");
                    return false;
                }
                $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
                $groupName = $args[1];
                if ($groups->getNested("Groups." . $groupName) == null) {
                    $sender->sendMessage($api->getSetting("error") . $api->getLang("grouperror"));
                    return true;
                }
                $perms = $groups->getNested("Groups.{$groupName}.permissions", []);
                $permission = $args[2];
                if (!in_array($permission, $perms)) {
                    $sender->sendMessage($api->getSetting("error") . $api->getLang("grouperrorperms"));
                    return true;
                }
                unset($perms[array_search($permission, $perms)]);
                $groups->setNested("Groups.{$groupName}.permissions", $perms);
                $groups->save();

                $message = str_replace("{group}" , $args[1], $api->getLang("groupremovepermsucces"));
                $message1 = str_replace("{perm}" , $args[2], $message);
                $sender->sendMessage($api->getSetting("gruppe") . $message1);
            }
            if ($args[0] == "default") {
                if (empty($args[1])) {
                    $sender->sendMessage($api->getSetting("info") ."Use : /group default {groupname}");
                    return true;
                }
                if ($groups->getNested("Groups." . $args[1]) == null) {
                    $sender->sendMessage($api->getSetting("error") . $api->getLang("grouperror"));
                    return true;
                }
                $groups->set("DefaultGroup", $args[1]);
                $groups->save();
                $message = str_replace("{group}" , $args[1], $api->getLang("groupdefaultsucces"));
                $sender->sendMessage($api->getSetting("gruppe") . $message);
            }
            if ($args[0] == "set") {
                if (empty($args[1])) {
                    $sender->sendMessage($api->getSetting("info") . "Use : /group set {player} {groupname}");
                    return false;
                }
                if (empty($args[2])) {
                    $sender->sendMessage($api->getSetting("info") . "Use : /group set {player} {groupname}");
                    return false;
                }
                $target = $api->findPlayer($sender, $args[1]);
                if ($target == null) {
                    $sender->sendMessage($api->getSetting("error") . $api->getLang("playernotonline"));
                    return false;
                }
                $name = $target->getName();
                $group = $args[2];
                if ($groups->getNested("Groups." . $group) == null) {
                    $sender->sendMessage($api->getSetting("error") . $api->getLang("grouperror"));
                    return false;
                }
                $groupprefix = $groups->getNested("Groups." . $group .".groupprefix");
                $playerdata->setNested($name . ".groupprefix", $groupprefix );
                $playerdata->setNested($name . ".group", $group);
                $playerdata->save();

                $playergroup = $playerdata->getNested($name.".group");
                $nametag = str_replace("{name}", $target->getName(), $groups->getNested("Groups.{$playergroup}.nametag"));
                $displayname = str_replace("{name}", $target->getName(), $groups->getNested("Groups.{$playerdata->getNested($name.".group")}.displayname"));
                $target->setNameTag($nametag);
                $target->setDisplayName($displayname);

                $permissionlist = (array)$groups->getNested("Groups.".$playergroup.".permissions", []);
                foreach($permissionlist as $name => $data) {
                    $target->addAttachment($this->plugin)->setPermission($data, true);
                }
                //$target->kick($api->getSetting("gruppe") . "§6Deine Gruppe wurde zu : $group §6geändert!\n§6Rejoine einfach den Server!", false);
                $message = str_replace("{group}" , $group, $api->getLang("groupsetsucces"));
                $message1 = str_replace("{player}" , $target->getName(), $message);
                $sender->sendMessage($api->getSetting("gruppe") . $message1);
            }
            if ($args[0] == "adduserperm") {
                if (empty($args[1])) {
                    $sender->sendMessage($api->getSetting("info") . "Use : /group adduserperm {player} {permission}");
                    return false;
                }
                if (empty($args[2])) {
                    $sender->sendMessage($api->getSetting("info") . "Use : /group adduserperm {player} {permission}");
                    return false;
                }
                $target = $api->findPlayer($sender, $args[1]);
                if ($target == null) {
                    $sender->sendMessage($api->getSetting("error") . $api->getLang("playernotonline"));
                    return false;
                }
                $spieler = $args[1];
                $permission = $args[2];
                $perms = $playerdata->getNested("$spieler.permissions", []);
                $perms[] = $permission;
                $playerdata->setNested("$spieler.permissions", $perms);
                $playerdata->save();

                $message = str_replace("{player}" , $args[1], $api->getLang("groupadduserpermsucces"));
                $message1 = str_replace("{perm}" , $args[2], $message);
                $sender->sendMessage($api->getSetting("gruppe") . $message1);
            }
            if ($args[0] == "removeuserperm") {
                if (empty($args[1])) {
                    $sender->sendMessage($api->getSetting("info") . "Use : /group removeuserperm {player} {permission}");
                    return false;
                }
                if (empty($args[2])) {
                    $sender->sendMessage($api->getSetting("info") . "Use : /group removeuserperm {player} {permission}");
                    return false;
                }
                $target = $api->findPlayer($sender, $args[1]);
                if ($target == null) {
                    $sender->sendMessage($api->getSetting("error") . $api->getLang("playernotonline"));
                    return false;
                }

                $spieler = $args[1];
                $permission = $args[2];
                $perms = $playerdata->getNested("$spieler.permissions", []);
                if (!in_array($permission, $perms)) {
                    $sender->sendMessage($api->getSetting("error") . $api->getLang("groupremoveuserpermerror"));
                    return true;
                }
                unset($perms[array_search($permission, $perms)]);
                $playerdata->setNested("$spieler.permissions", $perms);
                $playerdata->save();

                $message = str_replace("{player}" , $args[1], $api->getLang("groupremoveuserpermsucces"));
                $message1 = str_replace("{perm}" , $args[2], $message);
                $sender->sendMessage($api->getSetting("gruppe") . $message1);
            }
            if ($args[0] == "listperms") {
                $sender->sendMessage("Comming Soon...");
            }
        }
        return true;
    }
}