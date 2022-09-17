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

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\Explosion;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;

class NukeCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("nuke", $config->get("prefix") . $lang->get("nukeprefix"), "/nuke");
        $this->setPermission("core.command.nuke");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . $lang->get("commandingame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . $lang->get("nopermission"));
            return false;
        }
        if (isset($args[0])) {
            if ($sender->hasPermission("core.command.nuke.other")) {
                $victim = $this->plugin->getServer()->getPlayerExact($args[0]);
                $target = Server::getInstance()->getPlayerExact(strtolower($args[0]));
                if ($target == null) {
                    $sender->sendMessage($config->get("error") . $lang->get("playernotonline"));
                    return false;
                } else {
                    $explosion = new Explosion($sender->getPosition(),100);
                    $explosion->explodeA();
                    $explosion->explodeB();
                    $victim->sendMessage($config->get("prefix") . $lang->get("nuketarget"));
                    $message = str_replace("{victim}", $victim->getName(), $lang->get("nukesender"));
                    $sender->sendMessage($config->get("prefix") . $message);
                    return false;
                }
            } else {
                $sender->sendMessage($config->get("error") . $lang->get("nukenopermtarget"));
                return false;
            }
        }
        $explosion = new Explosion($sender->getPosition(),100);
        $explosion->explodeA();
        $explosion->explodeB();
        $sender->sendMessage($config->get("prefix") . $lang->get("nukesucces"));
        return true;
    }
}