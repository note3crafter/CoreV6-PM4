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
use TheNote\core\BaseAPI;
use TheNote\core\Main;
use TheNote\core\utils\ChestShopDataManager;

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
        $api = new BaseAPI();
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
                    $player->sendTip($api->getSetting("error") . "§cDu kannst nicht bei dir selbst kaufen!");
                    return;
                } else {
                    $event->cancel();
                }
                $buyerMoney = $api->getMoney($player->getName());

                if ($buyerMoney === false) {
                    $player->sendMessage($api->getSetting("error") . "§cFehler in der Matrix woops");
                    return;
                }
                if ($buyerMoney < $shopInfo['price']) {
                    $player->sendTip($api->getSetting("error") . "§cDu hast zu wenig Geld!");
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
                    $player->sendTip($api->getSetting("error") . "§cDieser Shop ist leider Ausverkauft!");
                    if (($p = $this->plugin->getServer()->getPlayerExact($shopInfo['shopOwner'])) !== null) {
                        $p->sendMessage($api->getSetting("error") . "§cDein Shop ist leider Leer! Bitte fülle ihn mit auf! §f:§e " . ItemFactory::getInstance()->get($pID, $pMeta)->getName());
                    }
                    return;
                }
                $item = ItemFactory::getInstance()->get((int)$shopInfo['productID'], (int)$shopInfo['productMeta'], (int)$shopInfo['saleNum']);
                $chest->getInventory()->removeItem($item);
                $player->getInventory()->addItem($item);
                $sellerMoney = $api->getMoney($shopInfo['shopOwner']);

                $chestShopIssuer = "ChestShop";
                if ($api->removeMoney($shopInfo['shopOwner'], (int)$shopInfo['price']) === $api->addMoney($shopInfo['shopOwner'], (int)$shopInfo['price'])) {
                    $player->sendTip($api->getSetting("money") . "§dDer Einkauf war erfolgreich!");
                    if (($p = $this->plugin->getServer()->getPlayerExact($shopInfo['shopOwner'])) !== null) {
                        $p->sendTip($api->getSetting("money") . "§e{$player->getName()} §dhat von dir §e" . ItemFactory::getInstance()->get($pID, $pMeta)->getName() . " §dfür§e " . $shopInfo['price'] . "§e$ §dgekauft!");
                    }
                } else {
                    $player->getInventory()->removeItem($item);
                    $chest->getInventory()->addItem($item);
                    $api->setMoney($player, $buyerMoney);
                    $api->setMoney($shopInfo['shopOwner'], $sellerMoney);
                    $player->sendTip($api->getSetting("error") . "§cDer Kauf ist Fehlgeschlagen!");
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
                    $player->sendMessage($api->getSetting("error") . "§cDieser Shop ist geschützt! Du hast keine Berechtigung dafür!");
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
        $api = new BaseAPI();
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
                        $player->sendMessage($api->getSetting("error") . "§cDieser Shop ist geschützt! Du hast keine Berechtigung dafür!");
                        $event->cancel();
                    } else {
                        $this->databaseManager->deleteByCondition($condition);
                        $player->sendMessage($api->getSetting("money") . "§dDein Shop wurde geschlossen!");
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
                        $player->sendMessage($api->getSetting("error") . "§cDieser Shop ist geschützt! Du hast keine Berechtigung dafür!");
                        $event->cancel();
                    } else {
                        $this->databaseManager->deleteByCondition($condition);
                        $player->sendMessage($api->getSetting("money") . "§dDein Shop wurde geschlossen!");
                    }
                }
                break;
        }
    }

    public function onSignChange(SignChangeEvent $event): void
    {
        $shopOwner = $event->getPlayer()->getName();
        $signText = $event->getNewText();
        $saleNum = (int)$signText->getLine(2);
        $price = (int)$signText->getLine(1);
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
                        0 => "§f[§e" . $shopOwner . "§f]",
                        1 => "§dPreis §f:§e " . $price . "§f$",
                        2 => "§dMenge §f: §e" . $saleNum,
                        3 => "§d" . $productName
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
            0 => "§f[§e" . $shopOwner . "§f]",
            1 => "§dPreis §f:§e " . $price . "§f$",
            2 => "§dMenge §f: §e" . $saleNum,
            3 => "§d" . $productName

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