<?php

namespace TheNote\core\utils;

use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

use onebone\economyapi\EconomyAPI;
use cooldogedev\BedrockEconomy\api\BedrockEconomyAPI;

class Manager implements Listener
{
    public static function formateString(Main $plugin, Player $player, string $string): string
    {
        $gruppe = new Config(Main::getInstance()->getDataFolder() . Main::$gruppefile . $player->getName() . ".json", Config::JSON);
        $playerdata = new Config(Main::getInstance()->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        $api = new BaseAPI();
        $string = str_replace([
            "{clan}",
            "{marry}",
            "{coins}",
            "{rank}",
            "{money}",
            "{ping}",
            "{tps}",
            "{name}",
            "{online}",
            "{max_online}",
            "{world}",
            "{x}",
            "{y}",
            "{z}",
            "{ip}",
            "{port}",
            "{uid}",
            "{xuid}",
            "{health}",
            "{max_health}",
            "{food}",
            "{max_food}",
            "{gamemode}",
            "{scale}",
            "{xplevel}",
            "{id}",
            "{meta}",
            "{count}",
            "{kicks}",
            "{joins}",
            "{breaks}",
            "{places}",
            "{drops}",
            "{picks}",
            "{interacts}",
            "{jumps}",
            "{messages}",
            "{votes}",
            "{flymeters}",
            "{walkmeters}",
            "{deaths}",
            "{consumes}"
            /*"{playtime}"*/],
            [$gruppe->get("Clan")
                , $api->getMarry($player->getName(), "partner")
                , $api->getCoins($player->getName())
                , $playerdata->getNested($player->getName() . ".groupprefix")
                , $api->getMoney($player->getName())
                , $player->getNetworkSession()->getPing()
                , Server::getInstance()->getTicksPerSecond()
                , $player->getName()
                , count(Server::getInstance()->getOnlinePlayers())
                , Server::getInstance()->getMaxPlayers()
                , $player->getWorld()->getFolderName()
                , round($player->getLocation()->getFloorX())
                , round($player->getLocation()->getFloorY())
                , round($player->getLocation()->getFloorZ())
                , $player->getNetworkSession()->getIp()
                , $player->getNetworkSession()->getPort()
                , $player->getUniqueId()
                , $player->getXuid()
                , $player->getHealth()
                , $player->getMaxHealth()
                , $player->getHungerManager()->getFood()
                , $player->getHungerManager()->getMaxFood()
                , $player->getGamemode()->getEnglishName()
                , $player->getScale()
                , $player->getXpManager()->getXpLevel()
                , $player->getInventory()->getItemInHand()->getId()
                , $player->getInventory()->getItemInHand()->getMeta()
                , $player->getInventory()->getItemInHand()->getCount()
                , $api->getKickPoints($player->getName())
                , $api->getJoinPoints($player->getName())
                , $api->getBreakPoints($player->getName())
                , $api->getPlacePoints($player->getName())
                , $api->getDropPoints($player->getName())
                , $api->getPickPoints($player->getName())
                , $api->getInteractPoints($player->getName())
                , $api->getJumpPoints($player->getName())
                , $api->getMessagePoints($player->getName())
                , $api->getVotePoints($player->getName())
                , round($api->getFlyPoints($player->getName()))
                , round($api->getWalkPoints($player->getName()))
                , $api->getDeathPoints($player->getName())
                , $api->getConsumePoints($player->getName())
                , /*$main->getDatabase()->getRawTime($player->getName())*/]
            , $string);
        return $string;
    }
}