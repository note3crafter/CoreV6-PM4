<?php

namespace TheNote\core\events;

use pocketmine\block\Block;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\utils\SignText;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\ItemFactory;
use pocketmine\math\Vector3;
use pocketmine\utils\Config;
use pocketmine\world\Position;
use TheNote\core\Main;
use TheNote\core\utils\ChestShopDataManager;
use onebone\economyapi\EconomyAPI;

class EconomyChest implements Listener
{

    private $plugin;
    private $databaseManager;

    public function __construct(Main $plugin, ChestShopDataManager $chestShopManager)
    {
        $this->plugin = $plugin;
        $this->databaseManager = $chestShopManager;
    }

    public function onPlayerInteract(PlayerInteractEvent $event): void
    {
        $block = $event->getBlock();
        $player = $event->getPlayer();
        $money = new Config($this->plugin->getDataFolder() . Main::$cloud . "Money.yml", Config::YAML);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);

        switch ($block->getID()) {
            case BlockLegacyIds::SIGN_POST:
            case BlockLegacyIds::WALL_SIGN:
                if (($shopInfo = $this->databaseManager->selectByCondition([
                        "signX" => $block->getPosition()->getFloorX(),
                        "signY" => $block->getPosition()->getFloorY(),
                        "signZ" => $block->getPosition()->getFloorZ()
                    ])) === false) return;
                $shopInfo = $shopInfo->fetchArray(SQLITE3_ASSOC);
                if ($shopInfo === false)
                    return;
                if ($shopInfo['shopOwner'] === $player->getName()) {
                    $player->sendTip($config->get("error") .  "§cDu kannst nicht bei dir selbst kaufen!");
                    return;
                } else {
                    $event->cancel();
                }
                if ($this->plugin->economyapi == null) {
                    $buyerMoney = $money->getNested("money." . $player->getName());
                } else {
                    $buyerMoney = EconomyAPI::getInstance()->myMoney($player->getName());
                }
                if ($buyerMoney === false) {
                    $player->sendMessage("Couldn't acquire your money data!");
                    return;
                }
                if ($buyerMoney < $shopInfo['price']) {
                    $player->sendTip($config->get("error") . "§cDu hast zu wenig Geld!");
                    return;
                }
                $chest = $player->getWorld()->getTile(new Vector3($shopInfo['chestX'], $shopInfo['chestY'], $shopInfo['chestZ']));
                $itemNum = 0;
                $pID = $shopInfo['productID'];
                $pMeta = $shopInfo['productMeta'];
                for ($i = 0; $i < $chest->getInventory()->getSize(); $i++) {
                    $item = $chest->getInventory()->getItem($i);
                    // use getDamage() method to get metadata of item
                    if ($item->getID() === $pID and $item->getMeta() === $pMeta) $itemNum += $item->getCount();
                }
                if ($itemNum < $shopInfo['saleNum']) {
                    $player->sendTip($config->get("error") . "§cDieser Shop ist leider Ausverkauft!");
                    if (($p = $this->plugin->getServer()->getPlayerExact($shopInfo['shopOwner'])) !== null) {
                        $p->sendMessage($config->get("error") . "§cDein Shop ist leider Leer! Bitte fülle ihn mit auf! §f:§e " . ItemFactory::getInstance()->get($pID, $pMeta)->getName());
                    }
                    return;
                }
                $item = ItemFactory::getInstance()->get((int)$shopInfo['productID'], (int)$shopInfo['productMeta'], (int)$shopInfo['saleNum']);
                $chest->getInventory()->removeItem($item);
                $player->getInventory()->addItem($item);
                if ($this->plugin->economyapi == null) {
                    $sellerMoney = $money->getNested("money." . $shopInfo['shopOwner']);
                } else {
                    $sellerMoney = EconomyAPI::getInstance()->myMoney($shopInfo['shopOwner']);
                }
                $chestShopIssuer = "ChestShop";
                if ($this->plugin->economyapi === null) {
                    if ($money->setNested("money." . $player->getName(), $money->getNested("money." . $player->getName()) - $shopInfo['price']) === $money->setNested("money." . $shopInfo['shopOwner'], $money->getNested("money." . $shopInfo['shopOwner']) + $shopInfo['price'])) {
                        $player->sendTip($config->get("money") . "§6Der Einkauf war erfolgreich!");
                        $money->save();
                        if (($p = $this->plugin->getServer()->getPlayerExact($shopInfo['shopOwner'])) !== null) {
                            $p->sendMessage("{$player->getName()} purchased " . ItemFactory::getInstance()->get($pID, $pMeta)->getName() . " for" . $shopInfo['price'] . "$");
                        }
                    } else {
                        $player->getInventory()->removeItem($item);
                        $chest->getInventory()->addItem($item);
                        $money->setNested("money." . $player->getName(), $buyerMoney);
                        $money->setNested("money." . $shopInfo['shopOwner'], $sellerMoney);
                        $money->save();
                        $player->sendTip($config->get("error") . "§cDer Kauf ist Fehlgeschlagen!");
                    }
                } else {
                    if (EconomyAPI::getInstance()->reduceMoney($player->getName(), $shopInfo['price'], null, $chestShopIssuer) === EconomyAPI::RET_SUCCESS and EconomyAPI::getInstance()->addMoney($shopInfo['shopOwner'], $shopInfo['price'], null, $chestShopIssuer) === EconomyAPI::RET_SUCCESS) {
                        $player->sendTip($config->get("money") . "§6Der Einkauf war erfolgreich!");
                        if (($p = $this->plugin->getServer()->getPlayerExact($shopInfo['shopOwner'])) !== null) {
                            $p->sendMessage("{$player->getName()} purchased " . ItemFactory::getInstance()->get($pID, $pMeta)->getName() . " for" . $shopInfo['price'] . "$");
                        }
                    } else {
                        $player->getInventory()->removeItem($item);
                        $chest->getInventory()->addItem($item);
                        EconomyAPI::getInstance()->setMoney($player->getName(), $buyerMoney);
                        EconomyAPI::getInstance()->setMoney($shopInfo['shopOwner'], $sellerMoney);
                        $player->sendTip($config->get("error") . "§cDer Kauf ist Fehlgeschlagen!");
                    }
                }
                break;

            case BlockLegacyIds::CHEST:
                $shopInfo = $this->databaseManager->selectByCondition([
                    "chestX" => $block->getPosition()->getX(),
                    "chestY" => $block->getPosition()->getY(),
                    "chestZ" => $block->getPosition()->getZ()
                ]);
                if ($shopInfo === false)
                    break;
                $shopInfo = $shopInfo->fetchArray(SQLITE3_ASSOC);
                if ($shopInfo !== false and $shopInfo['shopOwner'] !== $player->getName() and !$player->hasPermission("core.economy.chestshop.admin")) {
                    $player->sendMessage($config->get("error") . "§cDieser Shop ist geschützt! Du hast keine Berechtigung dafür!");
                    $event->cancel();
                }
                break;

            default:
                break;
        }
    }

    public function onPlayerBreakBlock(BlockBreakEvent $event): void
    {
        $block = $event->getBlock();
        $player = $event->getPlayer();
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        switch ($block->getID()) {
            case BlockLegacyIds::SIGN_POST:
            case BlockLegacyIds::WALL_SIGN:
                $condition = [
                    "signX" => $block->getPosition()->getFloorX(),
                    "signY" => $block->getPosition()->getFloorY(),
                    "signZ" => $block->getPosition()->getFloorZ()
                ];
                $shopInfo = $this->databaseManager->selectByCondition($condition);
                if ($shopInfo !== false) {
                    $shopInfo = $shopInfo->fetchArray();
                    if ($shopInfo === false)
                        break;
                    if ($shopInfo['shopOwner'] !== $player->getName() and !$player->hasPermission("core.economy.chestshop.admin")) {
                        $player->sendMessage($config->get("error") . "§cDieser Shop ist geschützt! Du hast keine Berechtigung dafür!");
                        $event->cancel();
                    } else {
                        $this->databaseManager->deleteByCondition($condition);
                        $player->sendMessage($config->get("money") . "§6Dein Shop wurde geschlossen!");
                    }
                }
                break;

            case BlockLegacyIds::CHEST:
                $condition = [
                    "chestX" => $block->getPosition()->getX(),
                    "chestY" => $block->getPosition()->getY(),
                    "chestZ" => $block->getPosition()->getZ()
                ];
                $shopInfo = $this->databaseManager->selectByCondition($condition);
                if ($shopInfo !== false) {
                    $shopInfo = $shopInfo->fetchArray();
                    if ($shopInfo === false)
                        break;
                    if ($shopInfo['shopOwner'] !== $player->getName() and !$player->hasPermission("core.economy.chestshop.admin")) {
                        $player->sendMessage($config->get("error") . "§cDieser Shop ist geschützt! Du hast keine Berechtigung dafür!");
                        $event->cancel();
                    } else {
                        $this->databaseManager->deleteByCondition($condition);
                        $player->sendMessage($config->get("money") . "§6Dein Shop wurde geschlossen!");
                    }
                }
                break;
        }
    }

    public function onSignChange(SignChangeEvent $event): void
    {
        $shopOwner = $event->getPlayer()->getName();
        $signText = $event->getNewText();
        $saleNum = (int)$signText->getLine(1);
        $price = (int)$signText->getLine(2);
        $productData = explode(":", $signText->getLine(3));
        $pID = $this->isItem($id = (int)array_shift($productData)) ? $id : false;
        $pMeta = ($meta = array_shift($productData)) ? (int)$meta : 0;

        $sign = $event->getBlock();

        // Check sign format...
        if ($signText->getLine(0) !== "") return;
        if (!is_numeric($saleNum) or $saleNum <= 0) return;
        if (!is_numeric($price) or $price < 0) return;
        if ($pID === false) return;
        if (($chest = $this->getSideChest($sign->getPosition())) === false) return;
        $shops = $this->databaseManager->selectByCondition(["shopOwner" => "'$shopOwner'"]);
        $res = true;
        $count = [];
        while ($res !== false) {
            $res = $shops->fetchArray(SQLITE3_ASSOC);
            if ($res !== false) {
                $count[] = $res;
                if ($res["signX"] === $event->getBlock()->getPosition()->getX() and $res["signY"] === $event->getBlock()->getPosition()->getY() and $res["signZ"] === $event->getBlock()->getPosition()->getZ()) {
                    $productName = ItemFactory::getInstance()->get($pID, $pMeta)->getName();
                    $event->setNewText(new SignText([
                        0 => "§f[§6" . $shopOwner . "§f]",
                        1 => "§ePreis §f:§e " . $price. "§f$",
                        2 => "§e" . $productName,
                        3 => "§eMenge §f: §e" . $saleNum
                    ]));

                    $this->databaseManager->registerShop($shopOwner, $saleNum, $price, $pID, $pMeta, $sign, $chest);
                    return;
                }
            }
        }
        if (empty($signText->getLine(3))) return;
        /*if (count($count) >= $this->plugin->getMaxPlayerShops($event->getPlayer()) and !$event->getPlayer()->hasPermission("chestshop.admin")) {
            $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permission to make more shops");
            return;
        }*/

        $productName = ItemFactory::getInstance()->get($pID, $pMeta)->getName();
        $event->setNewText(new SignText([
            0 => "§f[§6" . $shopOwner . "§f]",
            1 => "§ePreis §f:§e " . $price. "§f$",
            2 => "§e" . $productName,
            3 => "§eMenge §f: §e" . $saleNum

        ]));

        $this->databaseManager->registerShop($shopOwner, $saleNum, $price, $pID, $pMeta, $sign, $chest);
    }

    private function getSideChest(Position $pos): Block|bool
    {
        $block = $pos->getWorld()->getBlock(new Vector3($pos->getX() + 1, $pos->getY(), $pos->getZ()));
        if ($block->getID() === BlockLegacyIds::CHEST) return $block;
        $block = $pos->getWorld()->getBlock(new Vector3($pos->getX() - 1, $pos->getY(), $pos->getZ()));
        if ($block->getID() === BlockLegacyIds::CHEST) return $block;
        $block = $pos->getWorld()->getBlock(new Vector3($pos->getX(), $pos->getY() - 1, $pos->getZ()));
        if ($block->getID() === BlockLegacyIds::CHEST) return $block;
        $block = $pos->getWorld()->getBlock(new Vector3($pos->getX(), $pos->getY(), $pos->getZ() + 1));
        if ($block->getID() === BlockLegacyIds::CHEST) return $block;
        $block = $pos->getWorld()->getBlock(new Vector3($pos->getX(), $pos->getY(), $pos->getZ() - 1));
        if ($block->getID() === BlockLegacyIds::CHEST) return $block;
        return false;
    }

    private function isItem(int $id): bool
    {
        return ItemFactory::getInstance()->isRegistered($id);
    }
}