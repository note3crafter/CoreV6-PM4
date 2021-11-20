<?php


namespace TheNote\core\blocks;

use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockToolType;
use pocketmine\block\Transparent;

class CrimsonDoor extends Transparent
{
	public function __construct(BlockIdentifier $idInfo, ?BlockBreakInfo $breakInfo = null)
	{
		parent::__construct($idInfo, "Crimson Door", $breakInfo ?? new BlockBreakInfo(0.9, BlockToolType::AXE));
	}

	public function canBePlaced(): bool
	{
		return true;
	}
}