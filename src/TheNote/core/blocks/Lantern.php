<?php


namespace TheNote\core\blocks;


use pocketmine\block\Block;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockToolType;
use pocketmine\block\Opaque;
use pocketmine\block\Transparent;
use pocketmine\item\ToolTier;

class Lantern extends Transparent
{

	public function __construct(BlockIdentifier $idInfo, ?BlockBreakInfo $breakInfo = null)
	{
		parent::__construct($idInfo, "Lantern",$breakInfo ?? new BlockBreakInfo(5, BlockToolType::PICKAXE, ToolTier::WOOD()->getHarvestLevel()));
	}

	public function canBePlaced() : bool{
		return true;
	}
	protected function canAttachTo(Block $b) : bool{
		if($b->isTransparent()) {
			if($b instanceof Chain) return true;
			return false;
		}
		return true;
	}
}