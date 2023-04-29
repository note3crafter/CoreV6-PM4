<?php

namespace TheNote\core\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\math\AxisAlignedBB;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class NearCommand extends Command
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("near", $api->getSetting("prefix") . $api->getLang("nearprefix"), "/near");
        $this->setPermission("core.command.near");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("commandingame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("nopermission"));
            return false;
        }
        $player = $sender;
        if(isset($args[0])){
            $target = $api->findPlayer($sender, $args[0]);
            if(!$sender->hasPermission("core.command.near.other")){
                $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                return false;
            }elseif(!($player = $target)){
                $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                return false;
            }
        }
        $who = $player === $sender ? "you" : $player->getDisplayName();
        if(count($near = $this->getNearPlayers($player)) < 1){
            $m = TextFormat::GRAY . "** There are no players near to " . $who . TextFormat::GRAY . "! **";
        }else{
            $m = TextFormat::YELLOW . "** Hier sind " . (count($near) > 1 ? " Spieler " : "in der nähe ") . TextFormat::AQUA . count($near) . TextFormat::YELLOW . "player" . (count($near) > 1 ? "s " : " ") . "near to " . $who . TextFormat::YELLOW . ":";
            foreach($near as $p){
                $m .=  "§e\n*§r " . $p->getDisplayName();
            }
        }
        $sender->sendMessage($m);
        return true;
    }
    public function getNearPlayers(Player $player, int $radius = null): ?array{
        if($radius === null || !is_numeric($radius)){
            $radius = 100;
        }
        if(!is_numeric($radius)){
            return null;
        }
        $players = [];
        foreach($player->getWorld()->getNearbyEntities(new AxisAlignedBB($player->getPosition()->getFloorX() - $radius, $player->getPosition()->getFloorY() - $radius, $player->getPosition()->getFloorZ() - $radius, $player->getPosition()->getFloorX() + $radius, $player->getPosition()->getFloorY() + $radius, $player->getPosition()->getFloorZ() + $radius), $player) as $e){
            if($e instanceof Player){
                $players[] = $e;
            }
        }
        return $players;
    }

}