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
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\world\Position;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class WarpCommand extends Command
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("warp", $api->getSetting("prefix") . $api->getLang("warpprefix"), "/warp");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("commandingame"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($api->getSetting("info") . $api->getLang("warpusage"));
        }
        if (isset($args[0])) {
            $name = $args[0];
            $warp = new Config($this->plugin->getDataFolder() . Main::$cloud . "warps.json", Config::JSON);
            $x = $warp->getNested($args[0] . ".X");
            $y = $warp->getNested($args[0] . ".Y");
            $z = $warp->getNested($args[0] . ".Z");
            $world = $warp->getNested($args[0] . ".world");
            $gamemode = $warp->getNested($args[0] . ".gamemode");
            if ($gamemode === null){
                $sender->setGamemode(GameMode::SURVIVAL());
            } elseif ($gamemode === 0) {
                $sender->setGamemode(GameMode::SURVIVAL());
            } elseif ($gamemode === 1) {
                $sender->setGamemode(GameMode::CREATIVE());
            } elseif ($gamemode === 2) {
                $sender->setGamemode(GameMode::ADVENTURE());
            } elseif ($gamemode === 3) {
                $sender->setGamemode(GameMode::SPECTATOR());
            }
            if ($name === null) {
                $sender->sendMessage($api->getSetting("info") . $api->getLang("warpinfo"));
                return false;
            } else {
                if ($world == null) {
                    $message = str_replace("{warp}", $args[0], $api->getLang("warpnotexicst"));
                    $sender->sendMessage($api->getSetting("error") . $message);
                    return false;
                } else {
                    $this->plugin->getServer()->getWorldManager()->loadWorld($world);
					$sender->teleport(new Position($x , $y , $z, $this->plugin->getServer()->getWorldManager()->getWorldByName($world)));
                    $message = str_replace("{warp}", $args[0], $api->getLang("warpsucces"));
                    $sender->sendMessage($api->getSetting("prefix") . $message);
                }
                return false;
            }
        }
        return true;
    }
}