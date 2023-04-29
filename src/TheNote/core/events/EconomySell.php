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
use TheNote\core\BaseAPI;
use TheNote\core\Main;

use onebone\economyapi\EconomyAPI;
use cooldogedev\BedrockEconomy\api\BedrockEconomyAPI;

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

    public function onSignChange(SignChangeEvent $event): void
    {
        $api = new BaseAPI();
        $result = $this->tagExists($event->getNewText()->getLine(0));

        if ($result !== false) {
            $player = $event->getPlayer();
            if (!$player->hasPermission("core.economy.sell.create")) {
                $player->sendTip($api->getSetting("error") . "§cDu hast keine Berechtigung um einen Verkausshop zu erstellen!");
                return;
            }
            $signText = $event->getNewText();
            $count = (int)$signText->getLine(2);
            $price = (int)$signText->getLine(1);
            $productData = explode(":", $signText->getLine(3));
            $pID = $this->isItem($id = (int)array_shift($productData)) ? $id : false;
            $pMeta = ($meta = array_shift($productData)) ? (int)$meta : 0;
            $item = ItemFactory::getInstance()->get($pID, $pMeta)->getName();

            if (!is_numeric($count) /*or $count <= 0*/) {
                $player->sendTip($api->getSetting("error") . "§cDie Menge muss in Zahlen angegeben werden");
                return;
            }
            if (!is_numeric($price) /*or $price < 0*/) {
                $player->sendTip($api->getSetting("error") . "§cDer Preis muss in Zahlen angegeben werden");
                return;
            }
            if ($pID === false) {
                $player->sendTip($api->getSetting("error") . "§cDas Item wird nicht Unterstützt! §e");
                return;
            }
            if ($item === false) {
                $player->sendTip($api->getSetting("error") . "§cDas Item wird nicht Unterstützt! §e");
                return;
            }
            $block = $event->getBlock();
            $position = $block->getPosition();
            $this->sell[$position->getX() . ":" . $position->getY() . ":" . $position->getZ() . ":" . $player->getWorld()->getFolderName()] = array(
                "x" => $block->getPosition()->getX(),
                "y" => $block->getPosition()->getY(),
                "z" => $block->getPosition()->getZ(),
                "level" => $block->getPosition()->getWorld()->getFolderName(),
                "cost" => (int)$event->getNewText()->getLine(1),
                "item" => (int)$id,
                "itemName" => $item,
                "meta" => (int)$pMeta,
                "amount" => (int)$event->getNewText()->getLine(2)
            );
            $cfg = new Config($this->plugin->getDataFolder() . Main::$cloud . "Sell.yml", Config::YAML);
            $cfg->setAll($this->sell);
            $cfg->save();
            $player->sendTip($api->getSetting("money") . "§6Du hast den Verkaufsshop erfolgreich erstellt!");
            $event->setNewText(new SignText([
                0 => $result[0],
                1 => str_replace("{cost}", $price, $result[1]),
                2 => str_replace("{amount}", $count, $result[2]),
                3 => str_replace("{item}", $item, $result[3])
            ]));
        }
    }

    public function onTouch(PlayerInteractEvent $event)
    {
        $api = new BaseAPI();
        if ($event->getAction() !== PlayerInteractEvent::RIGHT_CLICK_BLOCK) {
            return;
        }
        $block = $event->getBlock();
        $position = $block->getPosition();
        $loc = $position->getX() . ":" . $position->getY() . ":" . $position->getZ() . ":" . $event->getPlayer()->getWorld()->getFolderName();

        if (isset($this->sell[$loc])) {
            $sell = $this->sell[$loc];
            $player = $event->getPlayer();

            if ($player->getGamemode()->getEnglishName() === "Creative") {
                $player->sendTip($api->getSetting("error") . "§cDu kannst nicht im Kreativmodus verkaufen!");
                $event->cancel();
                return;
            }

            if (!$player->hasPermission("core.economy.sell.sell")) {
                $player->sendTip($api->getSetting("error") . "§cDu hast keine Berechtigung um was zu verkaufen!");
                $event->cancel();
                return;
            }
            $cnt = 0;

            foreach ($player->getInventory()->getContents() as $item) {
                if ($item->getId() == $sell["item"] and $item->getMeta() == $sell["meta"]) {
                    $cnt += $item->getCount();
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
                $player->sendTip($api->getSetting("money") . "§cDrücke erneut um was zu verkaufen!");
                return;
            } else {
                unset($this->tap[$player->getName()]);
            }

            if ($cnt >= $sell["amount"]) {
                if ($this->plugin->economyapi == null /*and $this->plugin->bedrockeconomy == null*/) {
                    $api->addMoney($player, $sell["cost"]);
                    $this->removeItem($player, ItemFactory::getInstance()->get((int)$sell["item"], (int)$sell["meta"], (int)$sell["amount"]));
                }
                $player->sendTip($api->getSetting("money") . "§6Du hast erfolgreich was verkauft!");
            } else {
                $player->sendTip($api->getSetting("error") . "§cDu hast bereits alles verkauft!");
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
        $api = new BaseAPI();
        $block = $event->getBlock();
        if (isset($this->sell[$block->getPosition()->getX() . ":" . $block->getPosition()->getY() . ":" . $block->getPosition()->getZ() . ":" . $block->getPosition()->getWorld()->getFolderName()])) {
            $player = $event->getPlayer();
            if (!$player->hasPermission("core.economy.sell.remove")) {
                $player->sendTip($api->getSetting("error") . "§cDu hast keine Berechtigung um diesen Verkaufsshop zu zerstören!");
                $event->cancel();
                return;
            }
            $this->sell[$block->getPosition()->getX() . ":" . $block->getPosition()->getY() . ":" . $block->getPosition()->getZ() . ":" . $block->getPosition()->getWorld()->getFolderName()] = null;
            unset($this->sell[$block->getPosition()->getX() . ":" . $block->getPosition()->getY() . ":" . $block->getPosition()->getZ() . ":" . $block->getPosition()->getWorld()->getFolderName()]);
            $player->sendTip($api->getSetting("money") . "§6Der Verkaufsshop wurde erfolgreich entfernt.");
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

            if ($getitem->getId() === $setitem->getId() && $getitem->getMeta() === $setitem->getMeta()) {
                if ($getcount >= $setitem->getCount()) {
                    $getcount -= $setitem->getCount();
                    $sender->getInventory()->setItem($index, ItemFactory::air());
                } else if ($getcount < $setitem->getCount()) {
                    $sender->getInventory()->setItem($index, ItemFactory::getInstance()->get($getitem->getId(), $getitem->getMeta(), $setitem->getCount() - $getcount));
                    break;
                }
            }
        }
    }

    private function isItem(int $id): bool
    {
        return ItemFactory::getInstance()->isRegistered($id);
    }

    public function tagExists($tag)
    {
        foreach ($this->plugin->sellSign->getAll() as $key => $val) {
            if ($tag == $key) {
                return $val;
            }
        }
        return false;
    }
}
