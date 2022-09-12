<?php

namespace TheNote\core\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use TheNote\core\Main;

class SetHubCommand extends Command
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("sethub", $config->get("prefix") . "§aSetze den Hub", "/sethub");
        $this->setPermission("core.command.sethub");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $cfg = new Config($this->plugin->getDataFolder() . Main::$setup . "Config" . ".yml", Config::YAML);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        $level = $sender->getWorld()->getFolderName();
        $cfg->set("Defaultworld", $cfg->get("Defaultworld", $level));
        $cfg->save();
        $sender->sendMessage($config->get("prefix") . "§6Der HUB wurde gesetzt");
        return true;
    }
}