<?php


namespace TheNote\core\blocks;


use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockToolType;
use pocketmine\block\Opaque;

class WhiterRose extends Opaque
{
	public function __construct(BlockIdentifier $idInfo, ?BlockBreakInfo $breakInfo = null)
	{
		parent::__construct($idInfo, "Whiter Rose",$breakInfo ?? new BlockBreakInfo(0));
	}

	public function canBePlaced() : bool{
		return true;
	}
}