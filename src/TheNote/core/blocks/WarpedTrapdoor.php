<?php


namespace TheNote\core\blocks;


use pocketmine\block\Block;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockLegacyMetadata;
use pocketmine\block\BlockToolType;
use pocketmine\block\Opaque;
use pocketmine\block\utils\BlockDataSerializer;
use pocketmine\block\utils\HorizontalFacingTrait;
use pocketmine\item\Item;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;
use pocketmine\world\sound\DoorSound;

class WarpedTrapdoor extends Opaque
{

	public function __construct(BlockIdentifier $idInfo, ?BlockBreakInfo $breakInfo = null)
	{
		parent::__construct($idInfo, "Warped Trapdoor",$breakInfo ?? new BlockBreakInfo(0.9, BlockToolType::AXE));
	}

	public function canBePlaced() : bool{
		return true;
	}
	use HorizontalFacingTrait;

	protected bool $open = false;
	protected bool $top = false;

	protected function writeStateToMeta() : int{
		return BlockDataSerializer::write5MinusHorizontalFacing($this->facing) | ($this->top ? BlockLegacyMetadata::TRAPDOOR_FLAG_UPPER : 0) | ($this->open ? BlockLegacyMetadata::TRAPDOOR_FLAG_OPEN : 0);
	}

	public function readStateFromData(int $id, int $stateMeta) : void{

		$this->facing = BlockDataSerializer::read5MinusHorizontalFacing($stateMeta);
		$this->top = ($stateMeta & BlockLegacyMetadata::TRAPDOOR_FLAG_UPPER) !== 0;
		$this->open = ($stateMeta & BlockLegacyMetadata::TRAPDOOR_FLAG_OPEN) !== 0;
	}

	public function getStateBitmask() : int{
		return 0b1111;
	}

	public function isOpen() : bool{ return $this->open; }

	public function setOpen(bool $open) : self{
		$this->open = $open;
		return $this;
	}

	public function isTop() : bool{ return $this->top; }

	public function setTop(bool $top) : self{
		$this->top = $top;
		return $this;
	}

	protected function recalculateCollisionBoxes() : array{
		return [AxisAlignedBB::one()->trim($this->open ? $this->facing : ($this->top ? Facing::DOWN : Facing::UP), 13 / 16)];
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		if($player !== null){
			$this->facing = Facing::opposite($player->getHorizontalFacing());
		}
		if(($clickVector->y > 0.5 and $face !== Facing::UP) or $face === Facing::DOWN){
			$this->top = true;
		}

		return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		$this->open = !$this->open;
		$this->position->getWorld()->setBlock($this->position, $this);
		$this->position->getWorld()->addSound($this->position, new DoorSound());
		return true;
	}
}