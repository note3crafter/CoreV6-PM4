<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityItemPickupEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerBlockPickEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerJumpEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\OnScreenTextureAnimationPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class Stats implements Listener
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onJoin(PlayerJoinEvent $event) :void
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $stats = new Config($this->plugin->getDataFolder() . Main::$statsfile . $name . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $api = new BaseAPI();
        $api->addJoinPoints($player, 1);
        $serverstats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
        $serverstats->set("joins", $serverstats->get("joins") + 1);
        $serverstats->save();
        $joins = $api->getJoinPoints($player);
        if ($joins === 10000) {
            $player->sendMessage($config->get("erfolg") . "Toll du bist dem Server schon 10000x Beigetreten!");
            $this->plugin->addStrike($player);
            $this->screenanimation($player, 26);
			$packet = new PlaySoundPacket();
			$packet->soundName = "entity.player.levelup";
			$packet->x = $player->getPosition()->getX();
			$packet->y = $player->getPosition()->getY();
			$packet->z = $player->getPosition()->getZ();
			$packet->volume = 1;
			$packet->pitch = 1;
			$player->getNetworkSession()->sendDataPacket($packet);
			$stats->set("joinerfolg", true);
            $stats->save();
        }
    }

    public function break(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $stats = new Config($this->plugin->getDataFolder() . Main::$statsfile . $name . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $api = new BaseAPI();
        $api->addBreakPoints($player, 1);
        $serverstats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
        $serverstats->set("break", $serverstats->get("break") + 1);
        $serverstats->save();
        $breaks = $api->getBreakPoints($player);
        if ($breaks === 1000000) {
            $player->sendMessage($config->get("erfolg") . "Absoluter hammer! Du hast 1.000.000 Blöcke abgebaut! Das heißt, dass du ein Meisterminer bist! Glückwunsch! Als Belohnung bekommst du dafür 2500 Coins!");
            $this->plugin->addStrike($player);
            $this->screenanimation($player, 3);
			$packet = new PlaySoundPacket();
			$packet->soundName = "entity.player.levelup";
			$packet->x = $player->getPosition()->getX();
			$packet->y = $player->getPosition()->getY();
			$packet->z = $player->getPosition()->getZ();
			$packet->volume = 1;
			$packet->pitch = 1;
			$player->getNetworkSession()->sendDataPacket($packet);
            $api->addCoins($player, 2500);
            $stats->set("breakerfolg", true);
            $stats->save();
        }
    }

    public function place(BlockPlaceEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $stats = new Config($this->plugin->getDataFolder() . Main::$statsfile . $name . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $api = new BaseAPI();
        $api->addPlacePoints($player, 1);
        $serverstats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
        $serverstats->set("place", $serverstats->get("place") + 1);
        $serverstats->save();
        $place = $api->getPlacePoints($player);
        if ($place == 1000000) {
            $player->sendMessage($config->get("erfolg") . "Absoluter hammer! Du hast 1.000.000 Blöcke gesetzt! Das heißt, dass du ein Meisterbauer bist! Glückwunsch! Als Belohnung bekommst du dafür 2500 Coins!");
            $this->plugin->addStrike($player);
            $this->screenanimation($player, 3);
			$packet = new PlaySoundPacket();
			$packet->soundName = "entity.player.levelup";
			$packet->x = $player->getPosition()->getX();
			$packet->y = $player->getPosition()->getY();
			$packet->z = $player->getPosition()->getZ();
			$packet->volume = 1;
			$packet->pitch = 1;
			$player->getNetworkSession()->sendDataPacket($packet);
            $api->addCoins($player, 2500);
            $stats->set("placeerfolg", true);
            $stats->save();
        }
    }

    public function onKick(PlayerKickEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $stats = new Config($this->plugin->getDataFolder() . Main::$statsfile . $name . ".json", Config::JSON);
        $api = new BaseAPI();
        $api->addKickPoints($player, 1);
        $serverstats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
        $serverstats->set("kicks", $serverstats->get("kicks") + 1);
        $serverstats->save();
        $kick = $api->getKickPoints($player);
        if ($kick == 1000) {
			$packet = new PlaySoundPacket();
			$packet->soundName = "entity.player.levelup";
			$packet->x = $player->getPosition()->getX();
			$packet->y = $player->getPosition()->getY();
			$packet->z = $player->getPosition()->getZ();
			$packet->volume = 1;
			$packet->pitch = 1;
			$player->getNetworkSession()->sendDataPacket($packet);
            $stats->set("kickerfolg", true);
            $stats->save();
        }
    }

    public function onDeath(PlayerDeathEvent $event)
    {
        $player = $event->getPlayer();
        $api = new BaseAPI();
        $api->addDeathPoints($player, 1);
        $serverstats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
        $serverstats->set("deaths", $serverstats->get("deaths") + 1);
        $serverstats->save();
		$cause = $player->getLastDamageCause();
		if($cause instanceof EntityDamageByEntityEvent){
			$damager = $cause->getDamager();
			if($damager instanceof Player){
				$serverstats->set("kills", $serverstats->get("kills") + 1);
				$serverstats->save();
                $api->addKillPoints($player, 1);
            }
		}
    }

    public function onDrop(PlayerDropItemEvent $event)
    {
        $player = $event->getPlayer();
        $api = new BaseAPI();
        $api->addDropPoints($player, 1);
        $serverstats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
        $serverstats->set("drop", $serverstats->get("drop") + 1);
        $serverstats->save();
    }

    public function onChat(PlayerChatEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $stats = new Config($this->plugin->getDataFolder() . Main::$statsfile . $name . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $api = new BaseAPI();
        $api->addMessagePoints($player, 1);
        $serverstats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
        $serverstats->set("messages", $serverstats->get("messages") + 1);
        $serverstats->save();
        $message = $api->getMessagePoints($player);
        if ($message == 1000000) {
            $player->sendMessage($config->get("erfolg") . "Du hast soeben deine 1.000.000ste Nachrricht geschickt! Glückwunsch :D");
            $this->plugin->addStrike($player);
            $api->addCoins($player, 2500);
            $this->screenanimation($player, 27);
			$packet = new PlaySoundPacket();
			$packet->soundName = "entity.player.levelup";
			$packet->x = $player->getPosition()->getX();
			$packet->y = $player->getPosition()->getY();
			$packet->z = $player->getPosition()->getZ();
			$packet->volume = 1;
			$packet->pitch = 1;
			$player->getNetworkSession()->sendDataPacket($packet);
            $stats->set("messageerfolg", true);
            $stats->save();
        }
    }

    public function onPick(PlayerBlockPickEvent $event)
    {
        $player = $event->getPlayer();
        $api = new BaseAPI();
        $api->addPickPoints($player, 1);
        $serverstats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
        $serverstats->set("pick", $serverstats->get("pick") + 1);
        $serverstats->save();
    }

    public function onPickItem(EntityItemPickupEvent $event)
    {
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $pl) {
            $name = $pl->getName();
            $api = new BaseAPI();
            $api->addPickPoints($pl, 1);
            $serverstats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
            $serverstats->set("pick", $serverstats->get("pick") + 1);
            $serverstats->save();
        }
    }

    public function onConsume(PlayerItemConsumeEvent $event)
    {
        $player = $event->getPlayer();
        $api = new BaseAPI();
        $api->addConsumePoints($player, 1);
        $serverstats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
        $serverstats->set("consume", $serverstats->get("consume") + 1);
        $serverstats->save();
    }

    public function onInteract(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        $api = new BaseAPI();
        $api->addInteractPoints($player, 1);
        $serverstats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
        $serverstats->set("interact", $serverstats->get("interact") + 1);
        $serverstats->save();
    }

    public function onJump(PlayerJumpEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $api = new BaseAPI();
        $api->addJumpPoints($player, 1);
        $serverstats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
        $serverstats->set("jumps", $serverstats->get("jumps") + 1);
        $serverstats->save();
        $jumps = $api->getJumpPoints($player);
        if ($jumps == 10000) {
            $player->sendMessage($config->get("erfolg") . "Wow du hast nun 10000 Sprünge! Das hast du gut gemacht!");
            $this->plugin->addStrike($player);
            $this->screenanimation($player, 8);
			$packet = new PlaySoundPacket();
			$packet->soundName = "entity.player.levelup";
			$packet->x = $player->getPosition()->getX();
			$packet->y = $player->getPosition()->getY();
			$packet->z = $player->getPosition()->getZ();
			$packet->volume = 1;
			$packet->pitch = 1;
			$player->getNetworkSession()->sendDataPacket($packet);
        }
    }

    public function onmove(PlayerMoveEvent $event)
    {
        $player = $event->getPlayer();
        $to = $event->getTo()->round();
        $from = $event->getFrom()->round();
        $blocks = $from->distance($to);
        $round = round($blocks, 1);
        $api = new BaseAPI();
        if ($player->isFlying()) {
            $api->addFlyPoints($player, $round);
            $serverstats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
            $serverstats->set("movefly", $serverstats->get("movefly") + $round);
            $serverstats->save();
        } else {
            $api->addWalkPoints($player, $round);
            $serverstats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
            $serverstats->set("movewalk", $serverstats->get("movewalk") + $round);
            $serverstats->save();
        }
    }
    public function screenanimation(Player $player, int $effectID)
    {
        $packet = new OnScreenTextureAnimationPacket();
        $packet->effectId = $effectID;
        $player->getNetworkSession()->sendDataPacket($packet);
    }
}