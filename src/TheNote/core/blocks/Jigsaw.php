<?php


namespace TheNote\core\blocks;

use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockToolType;
use pocketmine\block\Opaque;

class Jigsaw extends Opaque
{

	public function __construct(BlockIdentifier $idInfo, ?BlockBreakInfo $breakInfo = null)
	{
		parent::__construct($idInfo, "Campfire",$breakInfo ?? new BlockBreakInfo(3600000, BlockToolType::AXE));
	}

	public function canBePlaced() : bool{
		return true;
	}
}