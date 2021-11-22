<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\blocks;

use pocketmine\block\Block;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockToolType;
use pocketmine\block\Opaque;
use pocketmine\block\tile\Spawnable;
use pocketmine\block\VanillaBlocks;
use pocketmine\entity\Entity;
use pocketmine\entity\object\ItemEntity;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;
use pocketmine\world\BlockTransaction;
use pocketmine\world\World;
use TheNote\core\Main;

class Campfire extends Opaque
{

	public function __construct(BlockIdentifier $idInfo, ?BlockBreakInfo $breakInfo = null)
	{
		parent::__construct($idInfo, "Campfire", $breakInfo ?? new BlockBreakInfo(2, BlockToolType::AXE));
	}

	public function getLightLevel(): int
	{
		return 15;
	}

	/*public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null): bool
	{
		$below = $this->getSide(Facing::DOWN);

		if ($below->getId() === ItemIds::AIR || $below->isTransparent() || $face !==Facing::UP) return false;

		$damage = 0;
		if ($player !== null) {
			if ($player->getDirectionVector() === 0) $damage = 3;
			else if ($player->getDirectionVector() === 1) $damage = 2;
			else if ($player->getDirectionVector() === 2) $damage = 1;
			else if ($player->getDirectionVector() === 3) $damage = 0;
		}

		$this->meta = $damage;
		$nbt = new nbt($this);
		$nbt->setInt('ItemTime1', 0);
		$nbt->setInt('ItemTime2', 0);
		$nbt->setInt('ItemTime3', 0);
		$nbt->setInt('ItemTime4', 0);
		$this->plugin->getServer()->getWorldManager()->getWorld()->setBlock($nbt,$blockReplace,true);
		//$this->plugin->getServer()->getWorldManager()->getWorld()->setBlock($blockReplace, new Placeholder($this, Tile::createTile("Campfire", $this->getLevel(), $nbt)), true, true);
		return true;
	}


	public function onNearbyBlockChange(): void
	{
		$below = $this->getSide(Facing::DOWN);
		if ($below->getId() === ItemIds::AIR || $below->isTransparent()) {
			$this->getWolrdo()->useBreakOn($this);
		}
	}

	public function getDrops(Item $item): array // Give a new item because the old items wouldn't stack (Due to damage?)
	{
		$tile = $this->getWorld()->getTile($this);
		if ($tile instanceof CampfireTile) {
			$drops = $tile->getItems();
			$drops[] = ItemFactory::get(Item::COAL, 1, 2);
		} else $drops = [];

		return $drops;
	}

	public function onActivate(Item $item, Player $player = null): bool
	{
		if ($player !== null) {
			$tile = $this->getWorld()->getTile($this);
			if ($tile instanceof CampfireTile) {
				if ($tile->canAddItem($player->getInventory()->getItemInHand())) {
					$tile->addItem($player->getInventory()->getItemInHand()->setCount(1));
					$item = $player->getInventory()->getItemInHand()->setCount($player->getInventory()->getItemInHand()->getCount() - 1);
					if (!$player->isCreative()) $player->getInventory()->setItemInHand($item);
					$player->getLevelNonNull()->broadcastLevelEvent($this->add(0.5, 0.5, 0.5), LevelEventPacket::EVENT_SOUND_ITEMFRAME_ADD_ITEM);
				}
			}
		}
		return true;
	}

	public function onEntityCollide(Entity $entity): void
	{
		$fire = true;
		if ($entity instanceof Player) {
			if ($entity->isCreative()) $fire = false;
			if ($entity->isOnFire()) $fire = false;
		}

		if ($entity instanceof ItemEntity) $fire = false;

		if ($fire) $entity->setOnFire(8);
		parent::onEntityCollide($entity);
	}

	public function hasEntityCollision(): bool
	{
		return true;
	}

	public function getPickedItem(): Item
	{
		return ItemFactory::get(BlockFactory::CAMPFIRE_ITEM);
	}*/
}