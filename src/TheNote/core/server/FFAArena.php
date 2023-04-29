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

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use onebone\economyapi\EconomyAPI;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use TheNote\core\Main;

class FFAArena implements Listener
{
	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
	}

	public function onDamage(EntityDamageByEntityEvent $event)
	{
		$config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
		$money = new Config($this->plugin->getDataFolder() . Main::$cloud . "Money.yml", Config::YAML);
		$cfg = new Config($this->plugin->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);

		$victim = $event->getEntity();
		$damager = $event->getDamager();

		if ($victim instanceof Player and $damager instanceof Player) {
			$name = $victim->getDisplayName();
			$dname = $damager->getDisplayName();
			$level = strtolower($victim->getWorld()->getFolderName());

			if ($level === $cfg->get("FFA_Map")) {

				if ($event->getEntity() instanceof Player) {
					$entity = $event->getEntity();
					$cause = $event->getCause();

					if ($cause == EntityDamageEvent::CAUSE_ENTITY_ATTACK) {

						if ($event instanceof EntityDamageByEntityEvent) {
							$damager = $event->getDamager();

							if ($damager instanceof Player) {
								if($event->getCause() == 4){
									$event->cancel();
									return;
								}


								/*$x = $entity->getX();
								$y = $entity->getY();
								$z = $entity->getZ();
								$xx = $entity->getWorld()->getSafeSpawn()->getX();
								$yy = $entity->getWorld()->getSafeSpawn()->getY();
								$zz = $entity->getWorld()->getSafeSpawn()->getZ();
								$sr = 8;

								if (abs($xx - $x) < $sr && abs($yy - $y) < $sr && abs($zz - $z) < $sr) {
									$event->isCancelled();
									$damager->sendMessage($config->get("prefix") . "§cDu bist im Spawnbereich!");
									return;
								} else*/

								if ($event->getFinalDamage() >= $victim->getHealth() && $victim instanceof Player) {
									if ($this->plugin->economy == null) {
										$money->getNested("money." . $name, $money->getNested("money." . $name));
									} else {
										$mymoney = EconomyAPI::getInstance()->myMoney($name);
									}
									$amount = round($mymoney * 0.001, 2);
									if ($amount <= $mymoney) {
										if ($this->plugin->economy == null) {
											$old = $money->getNested("money." . $name);
											$dold = $money->getNested("money." . $dname);
											$money->setNested("money." . $dname, $dold - $amount);
											$money->setNested("money." . $name, $old + $amount);
										} else {
											EconomyAPI::getInstance()->addMoney($dname, $amount);
											EconomyAPI::getInstance()->reduceMoney($name, $amount);
										}
										$victim->setHealth($victim->getMaxHealth());
										$victim->getHungerManager()->setFood(20);
										$victim->teleport($victim->getWorld()->getSafeSpawn());
										//$event->setKnockback(5);
										$victim->sendMessage($config->get("prefix") . $dname . " §chat dich getötet und von dir " . $amount . "$ §cgeklaut!");
										$damager->sendMessage($config->get("prefix") . " §aDu hast " . $name . " §cgetötet und hast von ihm " . $amount . "$ §eerhalten.");;
									}
								}
							}
						}
					}
				}
			}
		}
	}
}