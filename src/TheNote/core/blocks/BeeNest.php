<?php


namespace TheNote\core\blocks;


use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockToolType;
use pocketmine\block\Opaque;

class BeeNest extends Opaque
{
	public function __construct(BlockIdentifier $idInfo, ?BlockBreakInfo $breakInfo = null)
	{
		parent::__construct($idInfo, "Bee Nest",$breakInfo ?? new BlockBreakInfo(0.3, BlockToolType::AXE));
	}

	public function canBePlaced() : bool{
		return true;
	}
}