<?php

namespace TheNote\core;

use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class BaseAPI implements Listener
{
    //PlayerFinder
    public function findPlayer(CommandSender $sender, string $playerName) : ?Player{
        $langsettings = new Config(Main::getInstance()->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config(Main::getInstance()->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $config = new Config(Main::getInstance()->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);

        $subject = $sender->getServer()->getPlayerByPrefix($playerName);
        if($subject === null){
            //$sender->sendMessage($config->get("error") . $lang->get("playernotonline"));
            return null;
        }
        return $subject;
    }

    //EconomyAPI
    public function addMoney(Player $player, $amount)
    {
        $money = new Config(Main::getInstance()->getDataFolder() . Main::$cloud . "Money.yml", Config::YAML);
        $money->setNested("money." . $player->getName(), $money->getNested("money." . $player->getName()) + $amount);
        $money->save();
    }

    public function removeMoney(Player $player, $amount)
    {
        $money = new Config(Main::getInstance()->getDataFolder() . Main::$cloud . "Money.yml", Config::YAML);
        $money->setNested("money." . $player->getName(), $money->getNested("money." . $player->getName()) - $amount);
        $money->save();
    }

    public function getMoney(string $player)
    {
        $money = new Config(Main::getInstance()->getDataFolder() . Main::$cloud . "Money.yml", Config::YAML);
        $money->getNested("money." . $player);
        return $money->getNested("money." . $player);
    }

    public function setMoney(Player $player, $amount)
    {
        $money = new Config(Main::getInstance()->getDataFolder() . Main::$cloud . "Money.yml", Config::YAML);
        $money->setNested("money." . $player->getName(), $amount);
        $money->save();
    }

    public function getAllMoney()
    {
        $money = new Config(Main::getInstance()->getDataFolder() . Main::$cloud . "Money.yml", Config::YAML);
        $money->get("money", []);
    }


    //CoinAPI

    public function addCoins(Player $player, $amount)
    {
        $coins = new Config(Main::getInstance()->getDataFolder() . Main::$cloud . "Coins.yml", Config::YAML);
        $coins->setNested("coins." . $player->getName(), $coins->getNested("coins." . $player->getName()) + $amount);
        $coins->save();
    }

    public function removeCoins(Player $player, $amount)
    {
        $coins = new Config(Main::getInstance()->getDataFolder() . Main::$cloud . "Coins.yml", Config::YAML);
        $coins->setNested("coins." . $player->getName(), $coins->getNested("coins." . $player->getName()) - $amount);
        $coins->save();
    }

    public function getCoins(string $player)
    {
        $coins = new Config(Main::getInstance()->getDataFolder() . Main::$cloud . "Coins.yml", Config::YAML);
        $coins->getNested("coins." . $player);
        return $coins->getNested("coins." . $player);
    }

    public function setCoins(Player $player, $amount)
    {
        $coins = new Config(Main::getInstance()->getDataFolder() . Main::$cloud . "Coins.yml", Config::YAML);
        $coins->setNested("coins." . $player->getName(), $amount);
        $coins->save();
    }

    //StatsAPI
    public function addJoinPoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("joins", $stats->get("joins") + $points);
        $stats->save();
    }

    public function addBreakPoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("break", $stats->get("break") + $points);
        $stats->save();
    }

    public function addPlacePoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("place", $stats->get("place") + $points);
        $stats->save();
    }

    public function addKickPoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("kicks", $stats->get("kicks") + $points);
        $stats->save();
    }

    public function addDeathPoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("deaths", $stats->get("deaths") + $points);
        $stats->save();
    }

    public function addDropPoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("drop", $stats->get("drop") + $points);
        $stats->save();
    }

    public function addMessagePoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("messages", $stats->get("messages") + $points);
        $stats->save();
    }

    public function addPickPoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("pick", $stats->get("pick") + $points);
        $stats->save();
    }

    public function addConsumePoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("consume", $stats->get("consume") + $points);
        $stats->save();
    }

    public function addInteractPoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("interact", $stats->get("interact") + $points);
        $stats->save();
    }

    public function addJumpPoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("jumps", $stats->get("jumps") + $points);
        $stats->save();
    }

    public function addFlyPoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("movefly", $stats->get("movefly") + $points);
        $stats->save();
    }

    public function addWalkPoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("movewalk", $stats->get("movewalk") + $points);
        $stats->save();
    }

    public function addKillPoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("kills", $stats->get("kills") + $points);
        $stats->save();
    }

    public function addVotePoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("votes", $stats->get("votes") + $points);
        $stats->save();
    }

    public function getJoinPoints(string $player)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player . ".json", Config::JSON);
        $stats->get("joins");
        return $stats->get("joins");
    }

    public function getBreakPoints(string $player)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player . ".json", Config::JSON);
        $stats->get("break");
        return $stats->get("break");
    }

    public function getPlacePoints(string $player)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player . ".json", Config::JSON);
        $stats->get("place");
        return $stats->get("place");
    }

    public function getKickPoints(string $player)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player . ".json", Config::JSON);
        $stats->get("kicks");
        return $stats->get("kicks");
    }

    public function getDeathPoints(string $player)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player . ".json", Config::JSON);
        $stats->get("deaths");
        return $stats->get("deaths");
    }

    public function getDropPoints(string $player)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player . ".json", Config::JSON);
        $stats->get("drop");
        return $stats->get("drop");
    }

    public function getMessagePoints(string $player)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player . ".json", Config::JSON);
        $stats->get("messages");
        return $stats->get("messages");
    }

    public function getPickPoints(string $player)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player . ".json", Config::JSON);
        $stats->get("pick");
        return $stats->get("pick");
    }

    public function getConsumePoints(string $player)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player . ".json", Config::JSON);
        $stats->get("consume");
        return $stats->get("consume");
    }

    public function getInteractPoints(string $player)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player . ".json", Config::JSON);
        $stats->get("interact");
        return $stats->get("interact");
    }

    public function getJumpPoints(string $player)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player . ".json", Config::JSON);
        $stats->get("jumps");
        return $stats->get("jumps");
    }

    public function getFlyPoints(string $player)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player . ".json", Config::JSON);
        $stats->get("movefly");
        return $stats->get("movefly");
    }

    public function getWalkPoints(string $player)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player . ".json", Config::JSON);
        $stats->get("movewalk");
        return $stats->get("movewalk");
    }

    public function getKillPoints(string $player)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player . ".json", Config::JSON);
        $stats->get("kills");
        return $stats->get("kills");
    }

    public function getVotePoints(string $player)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . Main::$statsfile . $player . ".json", Config::JSON);
        $stats->get("votes");
        return $stats->get("votes");
    }

    //ClanAPI
    //MarryAPI
    public function getMarry($player, $marry)
    {
        $hei = new Config(Main::getInstance()->getDataFolder() . Main::$heifile . $player . ".json", Config::JSON);
        $x = $hei->get($marry);
        return $x;
    }
    public function addMarry($player, $marry, $result): bool
    {
        $hei = new Config(Main::getInstance()->getDataFolder() . Main::$heifile . $player . ".json", Config::JSON);
        $hei->set($marry, $result);
        $hei->save();
        return true;
    }
    //LangAPI
    public function getLang($lang)
    {
        $langsettings = new Config(Main::getInstance()->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $ls = $langsettings->get("Lang");
        $language = new Config(Main::getInstance()->getDataFolder() . Main::$lang . "Lang_" . $ls . ".json", Config::JSON);
        $l = $language->get($lang);
        return $l;
    }
    //UserdataAPI
    public function getUser(string $user, $data)
    {
        $usr = new Config(Main::getInstance()->getDataFolder() . Main::$userfile . $user . ".json", Config::JSON);
        return $usr->get($data);;
    }

    public function setUser(Player $user, $value, $data): bool
    {
        $usr = new Config(Main::getInstance()->getDataFolder() . Main::$userfile . $user->getName() . ".json", Config::JSON);
        $usr->set($value, $data);
        $usr->save();
        return true;
    }
    public function addUserPoint(Player $user, $amount): bool
    {
        $usr = new Config(Main::getInstance()->getDataFolder() . Main::$userfile . $user->getName() . ".json", Config::JSON);
        $usr->set($user->getName(), $usr->get($user->getName()) + $amount);
        $usr->save();
        return true;
    }
    public function rmUserPoint(Player $user, $amount): bool
    {
        $usr = new Config(Main::getInstance()->getDataFolder() . Main::$userfile . $user->getName() . ".json", Config::JSON);
        $usr->set($user->getName(), $usr->get($user->getName()) - $amount);
        $usr->save();
        return true;
    }
    //SetiingAPI
    public function getSetting($data)
    {
        $set = new Config(Main::getInstance()->getDataFolder() . Main::$setup . "settings.json", Config::JSON);
        return $set->get($data);
    }

    public function addSetting($setting, $data): bool
    {
        $set = new Config(Main::getInstance()->getDataFolder() . Main::$setup . "settings.json", Config::JSON);
        $set->set($setting, $data);
        $set->save();
        return true;
    }
    //ConfigAPI
    public function getConfig($configdata) {
        $cfg = new Config(Main::getInstance()->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        return $cfg->get($configdata);
    }
    //BackAPI
    public function getBack(string $player) {
        $back = new Config(Main::getInstance()->getDataFolder() . Main::$backfile . "Back.json", Config::JSON);
        return $back->get($player);
    }
    public function getBackExist(string $player): bool
    {
        $back = new Config(Main::getInstance()->getDataFolder() . Main::$backfile . "Back.json", Config::JSON);
        return $back->exists($player);
    }
    //HomeAPI
    public function getHome(string $player) {
        $home = new Config(Main::getInstance()->getDataFolder() . Main::$homefile . $player . ".json", Config::JSON);
        return $home->get($player);
    }
    public function setHome(Player $player, $home, $data): bool
    {
        $h = new Config(Main::getInstance()->getDataFolder() . Main::$homefile . $player->getName() . ".json", Config::JSON);
        $h->set($home, $data);
        $h->save();
        return true;
    }
    public function getHomeExist(string $player, $home): bool
    {
        $h = new Config(Main::getInstance()->getDataFolder() . Main::$homefile . $player . ".json", Config::JSON);
        return $h->exists($home);
    }
    public function setHomeRemove(Player $player, $home): bool
    {
        $h = new Config(Main::getInstance()->getDataFolder() . Main::$homefile . $player->getName() . ".json", Config::JSON);
        $h->remove($home);
        $h->save();
        return true;
    }
    //WarpAPI
    public function getWarp($warp) {
        $w = new Config(Main::getInstance()->getDataFolder() . Main::$cloud . "warps.json", Config::JSON);
        return $w->get($warp);
    }
    public function setWarp($warp, $data): bool
    {
        $w = new Config(Main::getInstance()->getDataFolder() . Main::$cloud . "warps.json", Config::JSON);
        $w->set($warp, $data);
        $w->save();
        return true;
    }
    public function getWarpExist($warp): bool
    {
        $w = new Config(Main::getInstance()->getDataFolder() . Main::$cloud . "warps.json", Config::JSON);
        return $w->exists($warp);
    }
    public function setWarpRemove($warp): bool
    {
        $w = new Config(Main::getInstance()->getDataFolder() . Main::$cloud . "warps.json", Config::JSON);
        $w->remove($warp);
        $w->save();
        return true;
    }
    //GroupAPI
}
