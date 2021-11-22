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
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockLegacyMetadata;
use pocketmine\block\BlockToolType;
use pocketmine\block\Button;
use pocketmine\block\Opaque;
use pocketmine\block\Transparent;
use pocketmine\block\utils\AnyFacingTrait;
use pocketmine\block\utils\BlockDataSerializer;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

class WarpedButton extends Transparent
{

	public function __construct(BlockIdentifier $idInfo, ?BlockBreakInfo $breakInfo = null)
	{
		parent::__construct($idInfo, "Warped Button",$breakInfo ?? new BlockBreakInfo(0.9, BlockToolType::AXE));
	}

	public function canBePlaced() : bool{
		return true;
	}
	use AnyFacingTrait;
	protected bool $pressed = false;


	protected function writeStateToMeta() : int{
		return BlockDataSerializer::writeFacing($this->facing) | ($this->pressed ? BlockLegacyMetadata::BUTTON_FLAG_POWERED : 0);
	}

	public function readStateFromData(int $id, int $stateMeta) : void{
		$this->facing = BlockDataSerializer::readFacing($stateMeta & 0x07);
		$this->pressed = ($stateMeta & BlockLegacyMetadata::BUTTON_FLAG_POWERED) !== 0;
	}

	public function getStateBitmask() : int{
		return 0b1111;
	}

	public function isPressed() : bool{ return $this->pressed; }

	public function setPressed(bool $pressed) : self{
		$this->pressed = $pressed;
		return $this;
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		$this->facing = $face;
		return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}

	/*abstract protected function getActivationTime() : int;


	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		if(!$this->pressed){
			$this->pressed = true;
			$this->position->getWorld()->setBlock($this->position, $this);
			$this->position->getWorld()->scheduleDelayedBlockUpdate($this->position, $this->getActivationTime());
			$this->position->getWorld()->addSound($this->position->add(0.5, 0.5, 0.5), new RedstonePowerOnSound());
		}

		return true;
	}

	public function onScheduledUpdate() : void{
		if($this->pressed){
			$this->pressed = false;
			$this->position->getWorld()->setBlock($this->position, $this);
			$this->position->getWorld()->addSound($this->position->add(0.5, 0.5, 0.5), new RedstonePowerOffSound());
		}
	}*/
}