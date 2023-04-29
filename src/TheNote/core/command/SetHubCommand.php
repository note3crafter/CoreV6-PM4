<?php

namespace TheNote\core\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class SetHubCommand extends Command
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("sethub", $api->getSetting("prefix") . $api->getLang("sethubprefix"), "/sethub");
        $this->setPermission("core.command.sethub");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        $cfg = new Config($this->plugin->getDataFolder() . Main::$setup . "Config" . ".yml", Config::YAML);
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("commandingame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("nopermission"));
            return false;
        }
        $level = $sender->getWorld()->getFolderName();
        $cfg->set("Defaultworld", $cfg->get("Defaultworld", $level));
        $cfg->save();
        $x = $sender->getLocation()->getX();
        $y = $sender->getLocation()->getY();
        $z = $sender->getLocation()->getZ();
        $world = $sender->getWorld()->getFolderName();

        $warp = new Config($this->plugin->getDataFolder() . Main::$cloud . "warps.json", Config::JSON);
        $warp->set("hub", ["X" => $x, "Y" => $y, "Z" => $z, "world" => $world]);
        $warp->save();
        $message = str_replace("{x}", $x, $api->getLang("sethubsucces"));
        $message1 = str_replace("{y}", $y , $message);
        $message2 = str_replace("{z}", $z , $message1);
        $message3 = str_replace("{world}", $world, $message2);
        $sender->sendMessage($api->getSetting("prefix") . $message3);

        return true;
    }
}