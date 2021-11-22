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
use pocketmine\block\Opaque;
use pocketmine\block\tile\ShulkerBox as TileShulkerBox;
use pocketmine\block\utils\AnyFacingTrait;
use pocketmine\block\utils\BlockDataSerializer;
use pocketmine\block\utils\FacesOppositePlacingPlayerTrait;
use pocketmine\block\utils\HorizontalFacingTrait;
use pocketmine\block\utils\InvalidBlockStateException;
use pocketmine\block\utils\NormalHorizontalFacingInMetadataTrait;
use pocketmine\block\utils\PillarRotationInMetadataTrait;
use pocketmine\block\utils\PillarRotationTrait;
use pocketmine\item\Item;
use pocketmine\math\Axis;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

class PolishedBasalt extends Block
{
	use PillarRotationInMetadataTrait;

	public function __construct(BlockIdentifier $idInfo, ?BlockBreakInfo $breakInfo = null)
	{
		parent::__construct($idInfo, "Polished Basalt", $breakInfo ?? new BlockBreakInfo(0.9, BlockToolType::PICKAXE));
	}
	protected function getAxisMetaShift() : int{
		return 0;
	}

}