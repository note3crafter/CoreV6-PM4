<?php


namespace TheNote\core\blocks;


use pocketmine\block\Block;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\BlockToolType;
use pocketmine\block\Flowable;
use pocketmine\block\Transparent;
use pocketmine\block\utils\BlockDataSerializer;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

class SoulTorch extends Flowable
{
	protected int $facing = Facing::UP;

	public function __construct(BlockIdentifier $idInfo, ?BlockBreakInfo $breakInfo = null)
	{
		parent::__construct($idInfo, "Soul Torch",$breakInfo ?? new BlockBreakInfo(0.9, BlockToolType::AXE));
	}

	public function getLightLevel() : int{
		return 14;
	}

	public function onNearbyBlockChange() : void{
		$below = $this->getSide(Facing::DOWN);
		$face = Facing::opposite($this->facing);

		if($this->getSide($face)->isTransparent() and !($face === Facing::DOWN and ($below->getId() === BlockLegacyIds::FENCE or $below->getId() === BlockLegacyIds::COBBLESTONE_WALL))){
			$this->position->getWorld()->useBreakOn($this->position);
		}
	}
	public function getFacing() : int{ return $this->facing; }

	public function setFacing(int $facing) : self{
		if($facing === Facing::DOWN){
			throw new \InvalidArgumentException("Torch may not face DOWN");
		}
		$this->facing = $facing;
		return $this;
	}
	public function readStateFromData(int $id, int $stateMeta) : void{
		$facingMeta = $stateMeta & 0x7;
		$this->facing = $facingMeta === 5 ? Facing::UP : BlockDataSerializer::readHorizontalFacing(6 - $facingMeta);
	}
	protected function writeStateToMeta() : int{
		return $this->facing === Facing::UP ? 5 : 6 - BlockDataSerializer::writeHorizontalFacing($this->facing);
	}
	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		if($blockClicked->canBeReplaced() and !$blockClicked->getSide(Facing::DOWN)->isTransparent()){
			$this->facing = Facing::UP;
			return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
		}elseif($face !== Facing::DOWN and (!$blockClicked->isTransparent() or ($face === Facing::UP and ($blockClicked->getId() === BlockLegacyIds::FENCE or $blockClicked->getId() === BlockLegacyIds::COBBLESTONE_WALL)))){
			$this->facing = $face;
			return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
		}else{
			foreach([
						Facing::SOUTH,
						Facing::WEST,
						Facing::NORTH,
						Facing::EAST,
						Facing::DOWN
					] as $side){
				$block = $this->getSide($side);
				if(!$block->isTransparent()){
					$this->facing = Facing::opposite($side);
					return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
				}
			}
		}
		return false;
	}
}