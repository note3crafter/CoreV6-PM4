<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\events;

use pocketmine\block\utils\SignText;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\Listener;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\item\ItemFactory;
use pocketmine\utils\Config;
use TheNote\core\Main;

class EconomySell implements Listener
{

    private $sell;
    private $placeQueue;
    private $sellSign;
    private $plugin;
    private $tap;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $this->placeQueue = [];
        $this->sell = (new Config($this->plugin->getDataFolder() . Main::$cloud . "Sell.yml", Config::YAML))->getAll();
    }

    public function getMessage($key, $val = array("{price}", "{item}", "{amount}"))
    {
        return str_replace(array("{price}", "{item}", "{amount}"), array($val[0], $val[1], $val[2]), $this->plugin->sellSign->get($key));
    }

    public function onSignChange(SignChangeEvent $event)
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $tag = $event->getNewText()->getLine(0);
        if (($result = $this->checkTag($tag)) !== false) {
            $player = $event->getPlayer();
            if (!$player->hasPermission("core.economy.sell.create")) {
                $player->sendMessage($config->get("error") . "§cDu hast keine Berechtigung um einen Verkaufsshop zu erstellen!");
                return;
            }
            if (!is_numeric($event->getOldText()->getLine(1)) or !is_numeric($event->getNewText()->getLine(3))) {
                return;
            }
			$item = ItemFactory::getInstance()->get($event->getOldText()->getLine(2));
            if ($item === false) {
                $player->sendMessage($this->getMessage($config->get("error") . "§cDas Item wird nicht Unterstützt! §e", array($event->getNewText()->getLine(2), "", "")));
                return;
            }

            $block = $event->getBlock();
            $this->sell[$block->getPosition()->getX() . ":" . $block->getPosition()->getY() . ":" . $block->getPosition()->getZ() . ":" . $player->getWorld()->getDisplayName()] = array(
                "x" => $block->getPosition()->getX(),
                "y" => $block->getPosition()->getY(),
                "z" => $block->getPosition()->getZ(),
                "level" => $player->getWorld(),
                "cost" => (int)$event->getNewText()->getLine(1),
                "item" => (int)$item->getID(),
                "itemName" => $item->getName(),
                "meta" => (int)$item->getDamage(),
                "amount" => (int)$event->getNewText()->getLine(3)
            );
            $cfg = new Config($this->plugin->getDataFolder() . Main::$cloud . "Sell.yml", Config::YAML);
            $cfg->setAll($this->sell);
            $cfg->save();
            $player->sendMessage($config->get("money") . "§6Du hast den Verkaufsshop erfolgreich erstellt!"/* . $sellcreate*/);

			$event->setNewText(new SignText([
				"§f[§aKaufen§f]",
				str_replace("{price}",$event->getOldText()->getLine(1),  $result[1]),
				str_replace("{item}", $item->getName(), $result[2]),
				str_replace("{amount}", $event->getOldText()->getLine(3), $result[3])
			]));
        }
    }

    public function onTouch(PlayerInteractEvent $event)
    {
        $money = new Config($this->plugin->getDataFolder() . Main::$cloud . "Money.yml", Config::YAML);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);

        if ($event->getAction() !== PlayerInteractEvent::RIGHT_CLICK_BLOCK) {
            return;
        }

        $block = $event->getBlock();
		$loc = $block->getPosition()->getX() . ":" . $block->getPosition()->getY() . ":" . $block->getPosition()->getZ() . ":" . $block->getPosition()->getWorld()->getFolderName();
        if (isset($this->sell[$loc])) {
            $sell = $this->sell[$loc];
            $player = $event->getPlayer();

            if (!$player->hasPermission("core.economy.sell.sell")) {
                $player->sendMessage($config->get("error") . "§cDu hast keine Berechtigung um was zu verkaufen!");
                $event->cancel();
                return;
            }
            $cnt = 0;
            foreach ($player->getInventory()->getContents() as $item) {
                if ($item->getID() == $sell["item"] and $item->getDamage() == $sell["meta"]) {
                    $cnt = $item->getCount();
                }
            }
            if (!isset($sell["itemName"])) {
                $item = ItemFactory::getInstance()->get($sell["item"], $sell["meta"], $sell["amount"]);
                if ($item === false) {
                    $item = $sell["item"] . ":" . $sell["meta"];
                } else {
                    $item = $item[0];
                }
                $this->sell[$loc]["itemName"] = $item;
                $sell["itemName"] = $item;
            }
            $now = microtime(true);
            if (!isset($this->tap[$player->getName()]) or $now - $this->tap[$player->getName()][1] >= 1.5 or $this->tap[$player->getName()][0] !== $loc) {
                $this->tap[$player->getName()] = [$loc, $now];
                $player->sendTip($config->get("money") . "§cDrücke erneut um was zu verkaufen!");
                return;
            } else {
                unset($this->tap[$player->getName()]);
            }

            if ($cnt >= $sell["amount"]) {
                $signsell = ItemFactory::getInstance()->get((int)$sell["item"], (int)$sell["meta"], (int)$sell["amount"]);
                $player->getInventory()->removeItem($signsell);
                if ($this->plugin->economyapi == null) {
                    $money->setNested("money." . $player->getName(), $money->getNested("money." . $player->getName()) + $sell ["cost"]);
                    $money->save();
                } else {
                    EconomyAPI::getInstance()->addMoney($player, $sell["price"]);
                }
                $player->sendTip($config->get("money") . "§6Du hast erfolgreich was verkauft!"/*,array($sell ["amount"], $sell ["item"].":".$sell ["meta"], $sell ["cost"])*/);
            } else {
                $player->sendTip($config->get("error") . "§cDu hast bereits alles verkauft!");
            }
            $event->cancel();
            if ($event->getItem()->canBePlaced()) {
                $this->placeQueue [$player->getName()] = true;
            }
        }
    }

    public function onPlace(BlockPlaceEvent $event)
    {
        $username = $event->getPlayer()->getName();
        if (isset($this->placeQueue [$username])) {
            $event->cancel();
            unset($this->placeQueue [$username]);
        }
    }

    public function onBreak(BlockBreakEvent $event)
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $block = $event->getBlock();
        if (isset($this->sell[$block->getPosition()->getX() . ":" . $block->getPosition()->getY() . ":" . $block->getPosition()->getZ() . ":" . $block->getPosition()->getWorld()->getFolderName()])) {
            $player = $event->getPlayer();
            if (!$player->hasPermission("core.economy.remove.sell")) {
                $player->sendMessage($config->get("error") . "§cDu hast keine Berechtigung um diesen Verkaufsshop zu zerstören!");
                $event->cancel();
                return;
            }
            $this->sell[$block->getPosition()->getX() . ":" . $block->getPosition()->getY() . ":" . $block->getPosition()->getZ() . ":" . $block->getPosition()->getWorld()->getFolderName()] = null;
            unset($this->sell[$block->getPosition()->getX() . ":" . $block->getPosition()->getY() . ":" . $block->getPosition()->getZ() . ":" . $block->getPosition()->getWorld()->getFolderName()]);
            $player->sendMessage($config->get("money") . "§6Der Verkaufsshop wurde erfolgreich entfernt.");
        }
    }

    public function checkTag($line1)
    {
        foreach ($this->plugin->sellSign->getAll() as $tag => $val) {
            if ($tag == $line1) {
                return $val;
            }
        }
        return false;
    }

    public function removeItem($sender, $getitem)
    {
        $getcount = $getitem->getCount();
        if ($getcount <= 0)
            return;
        for ($index = 0; $index < $sender->getInventory()->getSize(); $index++) {
            $setitem = $sender->getInventory()->getItem($index);
            if ($getitem->getID() == $setitem->getID() and $getitem->getDamage() == $setitem->getDamage()) {
                if ($getcount >= $setitem->getCount()) {
                    $getcount -= $setitem->getCount();
                    $sender->getInventory()->setItem($index, ItemFactory::getInstance()->get(0, 0, 1));
                } else if ($getcount < $setitem->getCount()) {
                    $sender->getInventory()->setItem($index, ItemFactory::getInstance()->get($getitem->getID(), 0, $setitem->getCount() - $getcount));
                    break;
                }
            }
        }
    }
}
