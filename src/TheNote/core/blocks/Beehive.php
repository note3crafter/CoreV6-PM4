<?php


namespace TheNote\core\blocks;


use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockToolType;
use pocketmine\block\Opaque;

class Beehive extends Opaque
{
	public function __construct(BlockIdentifier $idInfo, ?BlockBreakInfo $breakInfo = null)
	{
		parent::__construct($idInfo, "Bee Hive",$breakInfo ?? new BlockBreakInfo(0.6, BlockToolType::AXE));
	}

	public function canBePlaced() : bool{
		return true;
	}
}