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

use onebone\economyapi\EconomyAPI;

class EconomySell implements Listener
{
    private $sell;
    private $placeQueue;
    private $plugin;
    private $tap;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $this->placeQueue = [];
        $this->sell = (new Config($this->plugin->getDataFolder() . Main::$cloud . "Sell.yml", Config::YAML))->getAll();
    }

    public function onSignChange(SignChangeEvent $event)
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $tag = $event->getOldText()->getLine(0);
        if (($result = $this->checkTag($tag)) !== false) {
            $player = $event->getPlayer();
            if (!$player->hasPermission("core.economy.sell.create")) {
                $player->sendMessage($config->get("error") . "§cDu hast keine Berechtigung um einen Verkaufsshop zu erstellen!");
                return;
            }
			$signText = $event->getNewText();
			$count = (int)$signText->getLine(3);
			$price = (int)$signText->getLine(1);
			$productData = explode(":", $signText->getLine(2));
			$pID = $this->isItem($id = (int) array_shift($productData)) ? $id : false;
			$pMeta = ($meta = array_shift($productData)) ? (int)$meta : 0;
			$item = ItemFactory::getInstance()->get($pID, $pMeta)->getName();

			if (!is_numeric($count) or $count <= 0) {
				$player->sendMessage($config->get("error") . "§cDie Menge muss in Zahlen angegeben werden");
				return;
			}
			if (!is_numeric($price) or $price < 0) {
				$player->sendMessage($config->get("error") . "§cDer Preis muss in Zahlen angegeben werden");
				return;
			}
			if ($pID === false){
				$player->sendMessage($config->get("error") . "§cDas Item wird nicht Unterstützt! §e");
				return;
			}
			if($item === false){
				$player->sendMessage($config->get("error") . "§cDas Item wird nicht Unterstützt! §e");
				return;
			}

            $block = $event->getBlock();
            $this->sell[$block->getPosition()->getX() . ":" . $block->getPosition()->getY() . ":" . $block->getPosition()->getZ() . ":" . $player->getWorld()->getFolderName()] = array(
				"x" => $block->getPosition()->getX(),
				"y" => $block->getPosition()->getY(),
				"z" => $block->getPosition()->getZ(),
				"level" => $block->getPosition()->getWorld()->getFolderName(),
				"price" => (int)$event->getOldText()->getLine(1),
				"item" => (int)$id,
				"itemName" => $item,
				"meta" => (int)$pMeta,
				"amount" => (int)$event->getOldText()->getLine(3)
            );
            $cfg = new Config($this->plugin->getDataFolder() . Main::$cloud . "Sell.yml", Config::YAML);
            $cfg->setAll($this->sell);
            $cfg->save();
            $player->sendMessage($config->get("money") . "§6Du hast den Verkaufsshop erfolgreich erstellt!"/* . $sellcreate*/);

			$event->setNewText(new SignText([
				"§f[§aVerkaufen§f]",
				str_replace("{price}", $price, $result[1]),
				str_replace("{item}", $item, $result[2]),
				str_replace("{amount}", $count, $result[3])
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
                if ($item->getID() == $sell["item"] and $item->getMeta() == $sell["meta"]) {
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
            if (!$player->hasPermission("core.economy.sell.remove")) {
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
	private function isItem(int $id) : bool
	{
		return ItemFactory::getInstance()->isRegistered($id);
	}
}
