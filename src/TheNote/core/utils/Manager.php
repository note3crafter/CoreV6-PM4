<?php

namespace TheNote\core\utils;

use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\Main;

use onebone\economyapi\EconomyAPI;
use cooldogedev\BedrockEconomy\api\BedrockEconomyAPI;


class Manager implements Listener
{
    public static function formateString(Main $plugin, Player $player, string $string): string
    {

        $user = new Config(Main::getInstance()->getDataFolder() . Main::$userfile . $player->getName() . ".json", Config::JSON);
        $gruppe = new Config(Main::getInstance()->getDataFolder() . Main::$gruppefile . $player->getName() . ".json", Config::JSON);
        $online = new Config(Main::getInstance()->getDataFolder() . Main::$cloud . "Count.json", Config::JSON);
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player->getName() . ".json", Config::JSON);
        $hei = new Config(Main::getInstance()->getDataFolder() . Main::$heifile . $player->getName() . ".json", Config::JSON);
        $settings = new Config(Main::getInstance()->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $playerdata = new Config(Main::getInstance()->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        $money = new Config(Main::getInstance()->getDataFolder() . Main::$cloud . "Money.yml", Config::YAML);
        if($plugin->economyapi == null /*and $plugin->bedrockeconomy == null*/) {
            $moneystand = $money->getNested("money." . $player->getName());
        /*} elseif($plugin->economyapi == null) {
            $moneystand = BedrockEconomyAPI::legacy()->getPlayerBalance($player->getName());*/
        } else/*if($plugin->bedrockeconomy == null)*/ {
            $moneystand = EconomyAPI::getInstance()->myMoney($player->getName());
        }
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
            //"{meta}",
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
            "{consumes}"],
            [$gruppe->get("Clan")
                , $hei->get("heiraten")
                , $user->get("coins")
                , $playerdata->getNested($player->getName() . ".groupprefix")
                , $moneystand
                , $player->getNetworkSession()->getPing()
                , Server::getInstance()->getTicksPerSecond()
                , $player->getName()
                , count(Server::getInstance()->getOnlinePlayers())
                , Server::getInstance()->getMaxPlayers()
                , $player->getWorld()->getFolderName()
                , round($player->getLocation()->getX())
                , round($player->getLocation()->getY())
                , round($player->getLocation()->getZ())
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
                //, $player->getInventory()->getItemInHand()->getDamage()
                , $player->getInventory()->getItemInHand()->getCount()
                , $stats->get("kicks")
                , $stats->get("joins")
                , $stats->get("break")
                , $stats->get("place")
                , $stats->get("drop")
                , $stats->get("pick")
                , $stats->get("interact")
                , $stats->get("jumps")
                , $stats->get("messages")
                , $stats->get("votes")
                , round($stats->get("movefly"))
                , round($stats->get("movewalk"))
                , $stats->get("deaths")
                , $stats->get("consume")]
                , $string);
        return $string;
    }
}