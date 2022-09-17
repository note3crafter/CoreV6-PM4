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

use pocketmine\data\java\GameModeIdMap;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\Position;
use pocketmine\world\sound\EndermanTeleportSound;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;

class HubCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("hub", $config->get("prefix") . $lang->get("hubprefix"), "/hub", ["spawn", "lobby"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $configs = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "Config" . ".yml", Config::YAML);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . $lang->get("commandingame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . $lang->get("nopermission"));
            return false;
        }
        $sender->setGamemode(GameMode::fromString($config->get("Gamemode")));
        if ($config->get("Food") == true) {
            $sender->getHungerManager()->setFood(20);
        }
        if ($config->get("Heal") == true) {
            $sender->setHealth(20);
        }
        if ($config->get("Teleportsound") == true) {
            $sender->getWorld()->addSound($sender->getPosition(), new EndermanTeleportSound());
        }
        $warp = new Config($this->plugin->getDataFolder() . Main::$cloud . "warps.json", Config::JSON);
        $name = "hub";
        $x = $warp->getNested($name . ".X");
        $y = $warp->getNested($name . ".Y");
        $z = $warp->getNested($name . ".Z");
        $world = $warp->getNested($name . ".world");
        if ($name === null) {
            $sender->sendMessage($configs->get("error") . $lang->get("huberror"));
            return false;
        } else {
            if ($world == null) {
                $this->plugin->getServer()->getWorldManager()->loadWorld($world);
                $message = str_replace("{home}", $args[0], $lang->get("homeerror"));
                $sender->sendMessage($configs->get("error") . $message);
                return false;
            } else {
                $sender->teleport(new Position($x, $y, $z, $this->plugin->getServer()->getWorldManager()->getWorldByName($world)));
                $sender->sendMessage($configs->get("prefix") . $lang->get("hubsucces"));
                return true;
            }
        }
    }
}
