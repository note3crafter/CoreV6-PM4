<?php

namespace TheNote\core\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\data\bedrock\EffectIds;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class SpeedCommand extends Command
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("speed", $config->get("prefix") . $api->getLang("speedprefix"), "/speed");
        $this->setPermission("core.command.speed");

    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . $api->getLang("commandingame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . $api->getLang("nopermission"));
            return false;
        }
        if(!isset($args[0])) {
            $sender->sendMessage($config->get("info") . $api->getLang("speedusage"));
            return false;
        }
        if (!is_numeric($args[0])) {
            $sender->sendMessage($config->get("error") . $api->getLang("speednumb"));
            return false;
        }
        if((int) $args[0] === 0) {
            $sender->getEffects()->remove(VanillaEffects::SPEED());
        } elseif ((int)$args[0] > 255) {
            $sender->sendMessage($config->get("info") . $api->getLang("speedusage"));
            return false;
        } else {
            $sender->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), (2147483647), ($args[0]), false));
        }
        $message = str_replace("{speed}" ,  $args[0] , $api->getLang("speedsucces"));
        $sender->sendMessage($config->get("prefix") . $message);
        return true;
    }
}