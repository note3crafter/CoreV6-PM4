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

use pocketmine\block\Air;
use pocketmine\block\BambooSapling;
use pocketmine\block\Block;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\BlockLegacyMetadata;
use pocketmine\block\BrownMushroom;
use pocketmine\block\Cactus;
use pocketmine\block\DeadBush;
use pocketmine\block\Flowable;
use pocketmine\block\Flower;
use pocketmine\block\RedMushroom;
use pocketmine\block\Sapling;
use pocketmine\block\Slab;
use pocketmine\block\Stair;
use pocketmine\block\TallGrass;
use pocketmine\block\utils\SlabType;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\world\BlockTransaction;
use pocketmine\block\tile\FlowerPot as TileFlowerPot;
use pocketmine\player\Player;

class FlowerPot extends Flowable
{
    protected ?Block $plant = null;

    protected function writeStateToMeta(): int
    {
        //TODO: HACK! this is just to make the client actually render the plant - we purposely don't read the flag back
        return $this->plant !== null ? BlockLegacyMetadata::FLOWER_POT_FLAG_OCCUPIED : 0;
    }

    public function getStateBitmask(): int
    {
        return 0b1;
    }

    public function readStateFromWorld(): void
    {
        parent::readStateFromWorld();
        $tile = $this->position->getWorld()->getTile($this->position);
        if ($tile instanceof TileFlowerPot) {
            $this->setPlant($tile->getPlant());
        } else {
            $this->setPlant(null);
        }
    }

    public function writeStateToWorld(): void
    {
        parent::writeStateToWorld();
        $tile = $this->position->getWorld()->getTile($this->position);
        assert($tile instanceof TileFlowerPot);
        //$tile->setPlant($this->plant);
    }

    public function getPlant(): ?Block
    {
        return $this->plant;
    }

    public function setPlant(?Block $plant): self
    {
        if ($plant === null or $plant instanceof Air) {
            $this->plant = null;
        } else {
            $this->plant = clone $plant;
        }
        return $this;
    }

    public function canAddPlant(Block $block): bool
    {
        if ($this->plant !== null) {
            return false;
        }
        return $this->canBePlacedInFlowerPot($block);
    }

    protected function recalculateCollisionBoxes(): array
    {
        return [AxisAlignedBB::one()->contract(3 / 16, 0, 3 / 16)->trim(Facing::UP, 5 / 8)];
    }

    public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
            if(!$this->isValidSupport($this->getSide(Facing::DOWN))){
                return false;
            }
        return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
    }

    public function onNearbyBlockChange(): void
    {
        if (!$this->isValidSupport($this->getSide(Facing::DOWN))) {
            $this->position->getWorld()->useBreakOn($this->position);
        }
    }
    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null) : bool
    {
        $plant = $item->getBlock();

        if (!$this->canBePlacedInFlowerPot($plant)) {
            $this->setPlant(null);
        } elseif (!$this->canAddPlant($plant)) {
            return false;
        } else {
            if ($plant instanceof BambooSapling) {
                //Hack to convert bamboosapling to bamboo block (different id)
                $plant = VanillaBlocks::BAMBOO();
            }
            $this->setPlant($plant);
            $item->pop();
        }
        $this->position->getWorld()->setBlock($this->position, $this);

        return true;
    }
    public function getDropsForCompatibleTool(Item $item) : array{
        $items = parent::getDropsForCompatibleTool($item);
        if($this->plant !== null){
            $items[] = $this->plant->asItem();
        }
        return $items;
    }
    public function getPickedItem(bool $addUserData = false) : Item{
        return $this->plant !== null ? $this->plant->asItem() : parent::getPickedItem($addUserData);
    }
    protected function isValidSupport(Block $down): bool{
        if($down instanceof Slab && ($down->getSlabType()->equals(SlabType::TOP()) || $down->getSlabType()->equals(SlabType::DOUBLE()))){
            return true;
        }elseif($down instanceof Stair && $down->isUpsideDown()){
            return true;
        }
        switch($down->getId()){
            case BlockLegacyIds::BEACON:
            case BlockLegacyIds::GLASS:
            case BlockLegacyIds::FARMLAND:
            case BlockLegacyIds::GLOWSTONE:
            case BlockLegacyIds::GRASS_PATH:
            case BlockLegacyIds::HARD_STAINED_GLASS:
            case BlockLegacyIds::HOPPER_BLOCK:
            case BlockLegacyIds::FENCE:
            case BlockLegacyIds::STONE_WALL:
            case BlockLegacyIds::SEA_LANTERN:
                //TODO: piston, dropper
                return true;
        }
        return !$down->isTransparent();
    }

    protected function canBePlacedInFlowerPot(Block $block): bool{
        switch(true){
            case $block instanceof Cactus:
            case $block instanceof DeadBush:
            case $block instanceof Flower:
            case $block instanceof RedMushroom:
            case $block instanceof BrownMushroom:
            case $block instanceof Sapling:
            case $block instanceof BambooSapling:
            case $block instanceof Fungus:
            case $block instanceof Roots:
                //TODO: azaleas
                return true;
            case $block instanceof TallGrass:
                return $block->getIdInfo()->getVariant() === BlockLegacyMetadata::TALLGRASS_FERN;
        }
        return false;
    }
}
