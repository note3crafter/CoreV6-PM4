<?php


namespace TheNote\core\blocks;


use pocketmine\block\Block;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockLegacyMetadata;
use pocketmine\block\BlockToolType;
use pocketmine\block\Opaque;
use pocketmine\block\Stair;
use pocketmine\block\utils\BlockDataSerializer;
use pocketmine\block\utils\HorizontalFacingTrait;
use pocketmine\block\utils\StairShape;
use pocketmine\item\Item;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

class PolishedBlackstoneBrickStairs extends Opaque
{
	use HorizontalFacingTrait;
	protected bool $upsideDown = false;

	public function __construct(BlockIdentifier $idInfo, ?BlockBreakInfo $breakInfo = null)
	{
		parent::__construct($idInfo, "Polished Blackstone Brick Stairs", $breakInfo ?? new BlockBreakInfo(0.9, BlockToolType::PICKAXE));
	}

	public function canBePlaced(): bool
	{
		return true;
	}

	protected function writeStateToMeta(): int
	{
		return BlockDataSerializer::write5MinusHorizontalFacing($this->facing) | ($this->upsideDown ? BlockLegacyMetadata::STAIR_FLAG_UPSIDE_DOWN : 0);
	}

	public function readStateFromData(int $id, int $stateMeta): void
	{
		$this->facing = BlockDataSerializer::read5MinusHorizontalFacing($stateMeta);
		$this->upsideDown = ($stateMeta & BlockLegacyMetadata::STAIR_FLAG_UPSIDE_DOWN) !== 0;
	}

	public function getStateBitmask(): int
	{
		return 0b111;
	}

	public function readStateFromWorld(): void
	{
		parent::readStateFromWorld();

		$clockwise = Facing::rotateY($this->facing, true);
		if (($backFacing = $this->getPossibleCornerFacing(false)) !== null) {
			$this->shape = $backFacing === $clockwise ? StairShape::OUTER_RIGHT() : StairShape::OUTER_LEFT();
		} elseif (($frontFacing = $this->getPossibleCornerFacing(true)) !== null) {
			$this->shape = $frontFacing === $clockwise ? StairShape::INNER_RIGHT() : StairShape::INNER_LEFT();
		} else {
			$this->shape = StairShape::STRAIGHT();
		}
	}

	protected function recalculateCollisionBoxes(): array
	{
		$topStepFace = $this->upsideDown ? Facing::DOWN : Facing::UP;
		$bbs = [
			AxisAlignedBB::one()->trim($topStepFace, 0.5)
		];

		$topStep = AxisAlignedBB::one()
			->trim(Facing::opposite($topStepFace), 0.5)
			->trim(Facing::opposite($this->facing), 0.5);

		$bbs[] = $topStep;

		return $bbs;
	}

	private function getPossibleCornerFacing(bool $oppositeFacing): ?int
	{
		$side = $this->getSide($oppositeFacing ? Facing::opposite($this->facing) : $this->facing);
		return (
			$side instanceof Stair and
			$side->upsideDown === $this->upsideDown and
			Facing::axis($side->facing) !== Facing::axis($this->facing) //perpendicular
		) ? $side->facing : null;
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null): bool
	{
		if ($player !== null) {
			$this->facing = $player->getHorizontalFacing();
		}
		$this->upsideDown = (($clickVector->y > 0.5 and $face !== Facing::UP) or $face === Facing::DOWN);

		return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}
}
