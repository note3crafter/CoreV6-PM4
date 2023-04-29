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

use pocketmine\console\ConsoleCommandSender;
use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\PermissionManager;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use TheNote\core\BaseAPI;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class SeePermsCommand extends Command
{
    private $plugin;
    private $pmDefaultPerms = [];
    private $messages;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("seeperms", $api->getSetting("prefix") . "§6Siehe die Pluginberechtigung eines Plugins", "/seeperms [pluginname]", ["fperms"]);
        $this->setPermission("core.command.seeperms");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getSetting("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        if (!isset($args[0]) || count($args) > 2) {
            $sender->sendMessage($api->getSetting("info") . "Nutze : /seeperms [pluginname]");
            return true;
        }
        $plugin = (strtolower($args[0]) === 'pocketmine' || strtolower($args[0]) === 'pmmp') ? 'pocketmine' : $this->plugin->getServer()->getPluginManager()->getPlugin($args[0]);

        if ($plugin === null) {
            $sender->sendMessage($api->getSetting("error") . "§cDas Plugin §e" . $args[0] . " §cExistiert nicht!");

            return true;
        }
		$permissions = ($plugin instanceof PluginBase) ? $plugin->getDescription()->getPermissions() : $this->getPocketMinePerms();
        if (empty($permissions)) {
            $sender->sendMessage($api->getSetting("error") . "§cDas Plugin §e" . $args[0] . " §chat keine Berechtigung!");

            return true;
        }
        $pageHeight = $sender instanceof ConsoleCommandSender ? 48 : 6;
        $chunkedPermissions = array_chunk($permissions, $pageHeight);
        $maxPageNumber = count($chunkedPermissions);
        $permArray = PermissionManager::getInstance()->getPermissions(); //Gets all permissions objects
        $permName_DescArray = [];
        if (!isset($args[1]) || !is_numeric($args[1]) || $args[1] <= 0) {
            $pageNumber = 1;
        } else if ($args[1] > $maxPageNumber) {
            $pageNumber = $maxPageNumber;
        } else {
            $pageNumber = $args[1];
        }
        if (($plugin instanceof PluginBase) ? $plugin->getName() : 'PocketMine-MP') {
            $sender->sendMessage($api->getSetting("group") . "§6Seite §f: §e" . $pageNumber . "§f/§e" . $maxPageNumber);
        }

		/*foreach ($chunkedPermissions[$pageNumber - 1] as $permission) {
            $sender->sendMessage("§e" . $permission->getName());
        }
        return true;*/

        foreach($permArray as $perm)
        {
            $permName_DescArray[] = TextFormat::DARK_PURPLE."Permission: " . TextFormat::GREEN.$perm->getName() . TextFormat::YELLOW . " Description: " . $perm->getDescription(); //Message parsing
        }
        $this->plugin->getServer()->getLogger()->info("Here are all of the permission nodes:\n" . implode("\n", $permName_DescArray));
        return true;

    }
    public function getPlugin(): Plugin
    {
        return $this->plugin;
    }
    public function getPocketMinePerms()
    {
        if ($this->pmDefaultPerms === []) {
            foreach (PermissionManager::getInstance()->getPermissions() as $permission) {
                if (str_contains($permission->getName(), DefaultPermissions::ROOT_CONSOLE))
                    $this->pmDefaultPerms[] = $permission;
            }
        }
        return $this->pmDefaultPerms;
    }
}
