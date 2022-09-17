<?php

namespace TheNote\core\events;

use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\ItemFactory;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\tile\Chest;
use pocketmine\utils\Config;
use TheNote\core\Main;

class ChestShop implements Listener
{

    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $this->sell = (new Config($this->plugin->getDataFolder() . Main::$cloud . "Sell.yml", Config::YAML))->getAll();

    }

    public function onPlayerInteract(PlayerInteractEvent $event) :void
    {
        $block = $event->getBlock();
        $player = $event->getPlayer();
        $money = new Config($this->plugin->getDataFolder() . Main::$cloud . "Money.yml", Config::YAML);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $chestshop = new Config($this->plugin->getDataFolder() . Main::$cloud . "ChestShop" . ".yml", Config::YAML);

        switch ($block->getID()) {
            case Block::SIGN_POST:
            case Block::WALL_SIGN:
                if (($shopInfo = $this->databaseManager->selectByCondition([
                        "signX" => $block->getX(),
                        "signY" => $block->getY(),
                        "signZ" => $block->getZ()
                    ])) === false) return;
                if ($shopInfo['shopOwner'] === $player->getName()) {
                    $player->sendMessage("§f[§4N§fL] §6Du kannst nix von deinem Shop kaufen!!");
                    return;
                } else {
                    $event->setCancelled();
                }
                if ($this->plugin->economyapi == null) {
                    $buyerMoney = $money->getNested("money." . $player->getName());
                } else {
                    $buyerMoney = EconomyAPI::getInstance()->myMoney($player->getName());
                }

                if ($buyerMoney === false) {
                    $player->sendMessage("§f[§4N§fL] §6Konnte dein Geldstand nicht laden!");
                    return;
                }

                if ($buyerMoney < $shopInfo['price']) {
                    $player->sendTip($config->get("error") . "§cDu hast zu wenig geld um dir was zu kaufen!");

                    return;
                }
                $chest = $player->getLevel()->getTile(new Vector3($shopInfo['chestX'], $shopInfo['chestY'], $shopInfo['chestZ']));
                $itemNum = 0;
                $pID = $shopInfo['productID'];
                $pMeta = $shopInfo['productMeta'];
                for ($i = 0; $i < $chest->getInventory()->getSize(); $i++) {
                    $item = $chest->getInventory()->getItem($i);
                    // use getDamage() method to get metadata of item
                    if ($item->getID() === $pID and $item->getDamage() === $pMeta) $itemNum += $item->getCount();
                }
                if ($itemNum < $shopInfo['saleNum']) {
                    $player->sendMessage("§f[§4N§fL] §6Dieser Shop ist leider leer!");
                    if (($p = $this->plugin->getServer()->getPlayer($shopInfo['shopOwner'])) !== null) {
                        $p->sendMessage("§f[§4N§fL] §6Dein Shop ist leer bitte fülle in nach mit§f:§e " . ItemFactory::get($pID, $pMeta)->getName());
                    }
                    return;
                }

                $item = ItemFactory::get((int)$shopInfo['productID'], (int)$shopInfo['productMeta'], (int)$shopInfo['saleNum']);
                $chest->getInventory()->removeItem($item);
                $player->getInventory()->addItem($item);


                if ($this->plugin->economyapi == null) {
                    $sellerMoney = $money->getNested("money." . $shopInfo['shopOwner']);
                } else {
                    $sellerMoney = EconomyAPI::getInstance()->myMoney($shopInfo['shopOwner']);
                }



                if ($this->plugin->economyapi == null) {
                    if ($money->setNested("money." . $player->getName(), $money->getNested("money." . $player->getName()) - $shopInfo['price']) and $money->setNested("money." . $shopInfo['shopOwner'], $money->getNested("money." . $shopInfo['shopOwner']) + $shopInfo['price'])) {
                        $player->sendMessage("§f[§4N§fL] §6Transaktion war Erfolgreich");
                        $money->save();
                        if (($p = $this->plugin->getServer()->getPlayer($shopInfo['shopOwner'])) !== null) {
                            $p->sendMessage("{$player->getName()} purchased " . ItemFactory::get($pID, $pMeta)->getName() . " for " . $shopInfo['price']);
                        }
                        return;
                    } else {
                        $player->getInventory()->removeItem($item);
                        $chest->getInventory()->addItem($item);

                        $money->setNested("money." . $player->getName(), $money->getNested("money." . $player->getName()) + $buyerMoney);
                        $money->setNested("money." . $shopInfo['shopOwner'], $money->getNested("money." . $shopInfo['shopOwner']) + $sellerMoney);
                        $money->save();

                        $player->sendMessage("§f[§4N§fL] §6Transaktion fehlgeschlagen");
                    }
                } else {
                    if (EconomyAPI::getInstance()->reduceMoney($player->getName(), $shopInfo['price'], false, "ChestShop") === EconomyAPI::RET_SUCCESS and EconomyAPI::getInstance()->addMoney($shopInfo['shopOwner'], $shopInfo['price'], false, "ChestShop") === EconomyAPI::RET_SUCCESS) {
                        $player->sendMessage("§f[§4N§fL] §6Transaktion war Erfolgreich");
                        if (($p = $this->plugin->getServer()->getPlayer($shopInfo['shopOwner'])) !== null) {
                            $p->sendMessage("{$player->getName()} purchased " . ItemFactory::get($pID, $pMeta)->getName() . " for " . EconomyAPI::getInstance()->getMonetaryUnit() . $shopInfo['price']);
                        }
                    } else {
                        $player->getInventory()->removeItem($item);
                        $chest->getInventory()->addItem($item);

                        EconomyAPI::getInstance()->setMoney($player->getName(), $buyerMoney);
                        EconomyAPI::getInstance()->setMoney($shopInfo['shopOwner'], $sellerMoney);
                        $player->sendMessage("§f[§4N§fL] §6Transaktion fehlgeschlagen");
                    }
                }
                break;

            case Block::CHEST:
                $shopInfo = $this->databaseManager->selectByCondition([
                    "chestX" => $block->getX(),
                    "chestY" => $block->getY(),
                    "chestZ" => $block->getZ()
                ]);
                if ($shopInfo !== false and $shopInfo['shopOwner'] !== $player->getName()) {
                    $player->sendMessage("§f[§4N§fL] §6Dieser Shop ist geschützt!");
                    $event->setCancelled();
                }
                break;

            default:
                break;
        }
    }

    public function onPlayerBreakBlock(BlockBreakEvent $event)
    {
        $block = $event->getBlock();
        $player = $event->getPlayer();
        $chestshop = new Config($this->plugin->getDataFolder() . Main::$cloud . "ChestShop" . ".yml", Config::YAML);

        switch ($block->getID()) {
            case Block::SIGN_POST:
            case Block::WALL_SIGN:
                $condition = [
                    "signX" => $block->getX(),
                    "signY" => $block->getY(),
                    "signZ" => $block->getZ()
                ];

                $shopInfo = $this->databaseManager->selectByCondition($condition);
                if ($shopInfo !== false) {
                    if ($shopInfo['shopOwner'] !== $player->getName() and !$player->hasPermission("chestshop.deleteshop")) {
                        $player->sendMessage("§f[§4N§fL] §6Dieses Schild ist Geschützt!");
                        $event->setCancelled();
                    } else {
                        $this->sell[$block->getX() . ":" . $block->getY() . ":" . $block->getZ()] = null;
                        unset( $this->sell[$block->getX() . ":" . $block->getY() . ":" . $block->getZ()]);
                        $player->sendMessage("§f[§4N§fL] §6Dein Shop wurde Geschlossen");
                    }
                }
                break;

            case Block::CHEST:

                $data = $args[1];

                $chestshop->setNested($data . $block->getX());
                $chestshop->setNested($data . $block->getY());
                $chestshop->setNested($data . $block->getZ());
                $chestshop->save();
                if ($chestshop->getNested($data . "shopowner") !== $player->getName() and !$player->hasPermission("core.chestshop.delete")) {
                    $player->sendMessage("ChestShop geschützt");
                    $event->setCancelled();;
                }
                if ($shopInfo !== false) {
                    if ($shopInfo['shopOwner'] !== $player->getName() and !$player->hasPermission("chestshop.deleteshop")) {
                        $player->sendMessage("§f[§4N§fL] §6Diese Kiste ist Geschützt!");
                        $event->setCancelled();
                    } else {
                        $this->sell[$block->getX() . ":" . $block->getY() . ":" . $block->getZ()] = null;
                        unset( $this->sell[$block->getX() . ":" . $block->getY() . ":" . $block->getZ()]);
                        $player->sendMessage("§f[§4N§fL] §6Dein Shop wurde Geschlossen");
                    }
                }
                break;
        }
    }

    public function onSignChange(SignChangeEvent $event)
    {
        $shopOwner = $event->getPlayer()->getName();
        $saleNum = $event->getLine(1);
        $price = $event->getLine(2);
        $productData = explode(":", $event->getLine(3));
        $pID = $this->isItem($id = array_shift($productData)) ? (int)$id : false;
        $pMeta = ($meta = array_shift($productData)) ? (int)$meta : 0;
        $chestshop = new Config($this->plugin->getDataFolder() . Main::$cloud . "ChestShop" . ".yml", Config::YAML);

        $sign = $event->getBlock();

        // Check sign format...
        if ($event->getLine(0) !== "") return;
        if (!is_numeric($saleNum) or $saleNum <= 0) return;
        if (!is_numeric($price) or $price < 0) return;
        if ($pID === false) return;
        if (($chest = $this->getSideChest($sign)) === false) return;
        $shops = $this->databaseManager->selectByCondition(["shopOwner" => "'$shopOwner'"]);
        if (is_array($shops) and (count($shops) + 1 > $this->plugin->getMaxPlayerShops($event->getPlayer()))) return;

        $productName = ItemFactory::get($pID, $pMeta)->getName();
        $event->setLine(0, $shopOwner);
        $event->setLine(1, "Amount: $saleNum");
        $event->setLine(2, "Price: " . EconomyAPI::getInstance()->getMonetaryUnit() . $price);
        $event->setLine(3, $productName);

        $this->databaseManager->registerShop($shopOwner, $saleNum, $price, $pID, $pMeta, $sign, $chest);

        $chestshop->setNested();
        if (!$playerdata->exists($name)) {
            $groupprefix = $groups->getNested("Groups." . $defaultgroup . ".groupprefix");
            $playerdata->setNested($name . ".groupprefix", $groupprefix);
            $playerdata->setNested($name . ".group", $defaultgroup);
            $perms = $playerdata->getNested("{$name}.permissions", []);
            $perms[] = "CoreV5";
            $playerdata->setNested("{$name}.permissions", $perms);
            $playerdata->save();
        }

    }

    private function getSideChest(Position $pos)
    {
        $block = $pos->getLevel()->getBlock(new Vector3($pos->getX() + 1, $pos->getY(), $pos->getZ()));
        if ($block->getID() === Block::CHEST) return $block;
        $block = $pos->getLevel()->getBlock(new Vector3($pos->getX() - 1, $pos->getY(), $pos->getZ()));
        if ($block->getID() === Block::CHEST) return $block;
        $block = $pos->getLevel()->getBlock(new Vector3($pos->getX(), $pos->getY() - 1, $pos->getZ()));
        if ($block->getID() === Block::CHEST) return $block;
        $block = $pos->getLevel()->getBlock(new Vector3($pos->getX(), $pos->getY(), $pos->getZ() + 1));
        if ($block->getID() === Block::CHEST) return $block;
        $block = $pos->getLevel()->getBlock(new Vector3($pos->getX(), $pos->getY(), $pos->getZ() - 1));
        if ($block->getID() === Block::CHEST) return $block;
        return false;
    }

    private function isItem($id)
    {
        return ItemFactory::isRegistered((int)$id);
    }
}