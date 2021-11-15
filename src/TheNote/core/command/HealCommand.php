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

use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\player\Player;
use pocketmine\Server;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;

class HealCommand extends Command
{

    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("heal", $config->get("prefix") . "§6Heile dich oder einen anderen", "/heal");
        $this->setPermission("core.command.heal");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        if (isset($args[0])) {
            if ($sender->hasPermission("core.command.heal.other")) {
                $victim = $this->plugin->getServer()->getPlayerExact($args[0]);
                $target = Server::getInstance()->getPlayerExact(strtolower($args[0]));
                if ($target == null) {
                    $sender->sendMessage($config->get("error") . "Der Spieler ist nicht Online!");
                    return false;
                } else {
                    $sender->setAllowFlight(true);
                    $sender->setHealth(20);
                    $volume = mt_rand();
                    $sender->getWorld()->broadcastLevelSoundEvent($sender, LevelSoundEventPacket::SOUND_EAT, (int) $volume);
                    $sender->sendMessage($config->get("prefix") . "§6Du wurdest §eGeheilt§6 von " . $sender->getNameTag());
                    $sender->sendMessage($config->get("prefix") . "§6Du hast " .  $victim->getName() . " §eGeheilt§6.");
                    return false;
                }
            } else {
                $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um andere Spieler zu Füttern!");
                return false;
            }
        }
        $sender->setHealth(20);
        $sender->sendMessage($config->get("info") . "§6Du wurdest §eGeheilt§6.");
        return false;
    }
}