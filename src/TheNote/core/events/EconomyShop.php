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
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\item\ItemFactory;
use pocketmine\utils\Config;
use pocketmine\event\block\BlockPlaceEvent;
use TheNote\core\Main;

use onebone\economyapi\EconomyAPI;
use cooldogedev\BedrockEconomy\api\BedrockEconomyAPI;

class EconomyShop implements Listener
{
	private $shop;
	private $placeQueue;
	private $plugin;
	private $tap;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
		$this->placeQueue = [];
		$this->shop = (new Config($this->plugin->getDataFolder() . Main::$cloud . "Shop.yml", Config::YAML))->getAll();
	}

	public function onSignChange(SignChangeEvent $event): void
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);

        $result = $this->tagExists($event->getNewText()->getLine(0));

        if ($result !== false) {
            $player = $event->getPlayer();
            if (!$player->hasPermission("core.economy.shop.create")) {
                $player->sendMessage($config->get("error") . "§cDu hast keine Berechtigung um einen Shop zu erstellen!");
                return;
            }
            $signText = $event->getNewText();
            $count = (int)$signText->getLine(3);
            $price = (int)$signText->getLine(1);
            $productData = explode(":", $signText->getLine(2));
            $pID = $this->isItem($id = (int)array_shift($productData)) ? $id : false;
            $pMeta = ($meta = array_shift($productData)) ? (int)$meta : 0;
            $item = ItemFactory::getInstance()->get($pID, $pMeta)->getName();

            if (!is_numeric($count) /*or $count <= 0*/) {
                $player->sendMessage($config->get("error") . "§cDie Menge muss in Zahlen angegeben werden");
                return;
            }
            if (!is_numeric($price) /*or $price < 0*/) {
                $player->sendMessage($config->get("error") . "§cDer Preis muss in Zahlen angegeben werden");
                return;
            }
            if ($pID === false) {
                $player->sendMessage($config->get("error") . "§cDas Item wird nicht Unterstützt! §e");
                return;
            }
            if ($item === false) {
                $player->sendMessage($config->get("error") . "§cDas Item wird nicht Unterstützt! §e");
                return;
            }
            $block = $event->getBlock();
            $position = $block->getPosition();
            $this->shop[$position->getX().":".$position->getY().":".$position->getZ().":".$player->getWorld()->getFolderName()] = array(
                "x" => $block->getPosition()->getX(),
                "y" => $block->getPosition()->getY(),
                "z" => $block->getPosition()->getZ(),
                "level" => $block->getPosition()->getWorld()->getFolderName(),
                "price" => (int)$event->getNewText()->getLine(1),
                "item" => (int)$id,
                "itemName" => $item,
                "meta" => (int)$pMeta,
                "amount" => (int)$event->getNewText()->getLine(3)
            );
            $cfg = new Config($this->plugin->getDataFolder() . Main::$cloud . "Shop.yml", Config::YAML);
            $cfg->setAll($this->shop);
            $cfg->save();
            $player->sendMessage($config->get("money") . "§6Du hast den Shop erfolgreich erstellt!");
            $event->setNewText(new SignText([
                0 => $result[0],
                1 => str_replace("{price}", $price, $result[1]),
                2 => str_replace("{item}", $item, $result[2]),
                3 => str_replace("{amount}", $count, $result[3])
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
        $position = $block->getPosition();
        $loc = $position->getX().":".$position->getY().":".$position->getZ().":".$event->getPlayer()->getWorld()->getFolderName();
        if (isset($this->shop[$loc])) {
            $shop = $this->shop[$loc];
            $player = $event->getPlayer();
            if($player->getGamemode()->getEnglishName() === "Creative") {
                $player->sendTip($config->get("error") . "§cDu kannst nicht im Kreativmodus kaufen!");
                $event->cancel();
                return;
            }
            if (!$player->hasPermission("core.economy.shop.buy")) {
                $player->sendTip($config->get("error") . "§cDu hast keine Berechtigung um was zu kaufen!");
                $event->cancel();
                return;
            }
            if (!$player->getInventory()->canAddItem(ItemFactory::getInstance()->get($shop["item"], $shop["meta"]))) {
                $player->sendTip($config->get("error") . "§cDein Inventar ist voll! Leere es bevor du was Kaufst");
                return;
            }
            if ($this->plugin->economyapi == null) {
                $geld = $money->getNested("money." . $player->getName());
            } else {
                $geld = EconomyAPI::getInstance()->myMoney($player);
            }
            if ($shop["price"] > $geld) {
                $player->sendTip($config->get("error") . "§cDu hast zu wenig geld um dir was zu kaufen!" /*. [$shop["item"] . ":" . $shop["meta"], $shop["price"]]*/);
                $event->cancel();
                if ($event->getItem()->canBePlaced()) {
                    $this->placeQueue[$player->getName()] = true;
                }
                return;
            } else {
                if (!isset($shop["itemName"])) {
                    $item = ItemFactory::getInstance()->get($shop["item"], $shop["meta"], $shop["amount"]);
                    if ($item === false) {
                        $item = $shop["{item}"] . ":" . $shop["meta"];
                    } else {
                        $item = $item[0];
                    }
                    $this->shop[$loc]["itemName"] = $item;
                    $shop["itemName"] = $item;
                }
                $now = microtime(true);

                if(!isset($this->tap[$player->getName()]) or $now - $this->tap[$player->getName()][1] >= 1.5  or $this->tap[$player->getName()][0] !== $loc){
                    $this->tap[$player->getName()] = [$loc, $now];
                    $player->sendTip($config->get("money") . "§cDrücke erneut um was zu kaufen!");
                    return;
                }else{
                    unset($this->tap[$player->getName()]);
                }
                $signshop = ItemFactory::getInstance()->get((int)$shop ["item"], (int)$shop["meta"], (int)$shop["amount"]);
                $player->getInventory()->addItem($signshop);
                if ($this->plugin->economyapi == null /*and $this->plugin->bedrockeconomy == null*/) {
                    $money->setNested("money." . $player->getName(), $money->getNested("money." . $player->getName()) - $shop ["price"]);
                    $money->save();
                } else/*if($this->plugin->bedrockeconomy == null)*/ {
                    EconomyAPI::getInstance()->reduceMoney($player, $shop["price"]);
                } /*elseif($this->plugin->economyapi == null) {
                    BedrockEconomyAPI::legacy()->subtractFromPlayerBalance($player, $shop["price"]);
                }*/
                $player->sendTip($config->get("money") . "§6Du hast erfolgreich was gekauft!" /*. [$shop["amount"], $shop["itemName"], $shop["price"]]*/);
                $event->cancel(true);
                if ($event->getItem()->canBePlaced()) {
                    $this->placeQueue[$player->getName()] = true;
                }
            }
        }
    }

    public function onPlaceEvent(BlockPlaceEvent $event){
        $username = $event->getPlayer()->getName();
        if(isset($this->placeQueue[$username])){
            $event->cancel(true);
            unset($this->placeQueue[$username]);
        }
    }

    public function onBreakEvent(BlockBreakEvent $event){
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $block = $event->getBlock();
        if(isset($this->shop[$block->getPosition()->getX().":".$block->getPosition()->getY().":".$block->getPosition()->getZ().":".$block->getPosition()->getWorld()->getFolderName()])){
            $player = $event->getPlayer();
            if(!$player->hasPermission("core.economy.shop.remove")){
                $player->sendTip($config->get("error") . "§cDu hast keine Berechtigung um diesen Shop zu zerstören!");
                $event->cancel();
                return;
            }
            $this->shop[$block->getPosition()->getX().":".$block->getPosition()->getY().":".$block->getPosition()->getZ().":".$block->getPosition()->getWorld()->getFolderName()] = null;
            unset($this->shop[$block->getPosition()->getX().":".$block->getPosition()->getY().":".$block->getPosition()->getZ().":".$block->getPosition()->getWorld()->getFolderName()]);
            $player->sendTip($config->get("money") . "§6Der Shop wurde erfolgreich entfernt.");
        }
    }
    public function tagExists($tag){
        foreach($this->plugin->shopSign->getAll() as $key => $val){
            if($tag == $key){
                return $val;
            }
        }
        return false;
    }
	private function isItem(int $id) : bool
	{
		return ItemFactory::getInstance()->isRegistered($id);
	}
}