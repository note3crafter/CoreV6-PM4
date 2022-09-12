<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\generators\normal\biome;

use TheNote\core\server\generators\nether\populator\Ore;
use TheNote\core\server\generators\normal\biome\types\CoveredBiome;
use TheNote\core\server\generators\normal\populator\impl\CactusPopulator;
use TheNote\core\server\generators\normal\populator\impl\plant\Plant;
use TheNote\core\server\generators\normal\populator\impl\PlantPopulator;
use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use pocketmine\world\generator\object\OreType;

class Badlands extends CoveredBiome {

	public function __construct() {
		parent::__construct(2, 0);

		$this->setGroundCover([
			VanillaBlocks::RED_SAND(),
			VanillaBlocks::HARDENED_CLAY(),
			VanillaBlocks::STAINED_CLAY()->setColor(DyeColor::RED()),
			VanillaBlocks::STAINED_CLAY()->setColor(DyeColor::YELLOW()),
			VanillaBlocks::STAINED_CLAY()->setColor(DyeColor::YELLOW()),
			VanillaBlocks::STAINED_CLAY()->setColor(DyeColor::BROWN()),
			VanillaBlocks::STAINED_CLAY()->setColor(DyeColor::WHITE()),
			VanillaBlocks::STAINED_CLAY()->setColor(DyeColor::ORANGE()),
			VanillaBlocks::STAINED_CLAY()->setColor(DyeColor::RED()),
			VanillaBlocks::STAINED_CLAY()->setColor(DyeColor::YELLOW()),
			VanillaBlocks::STAINED_CLAY()->setColor(DyeColor::YELLOW()),
			VanillaBlocks::STAINED_CLAY()->setColor(DyeColor::BROWN()),
			VanillaBlocks::STAINED_CLAY()->setColor(DyeColor::WHITE()),
			VanillaBlocks::STAINED_CLAY()->setColor(DyeColor::ORANGE())
		]);

		$cactus = new CactusPopulator(3, 2);

		$deadBush = new PlantPopulator(3, 2);
		$deadBush->addPlant(new Plant(VanillaBlocks::DEAD_BUSH(), [VanillaBlocks::STAINED_CLAY()]));

		$ore = new Ore();
		$ore->setOreTypes([new OreType(VanillaBlocks::GOLD_ORE(), VanillaBlocks::STONE(), 12, 0, 0, 128)]);

		$this->addPopulators([
			$cactus,
			$deadBush,
			$ore
		]);

		$this->setElevation(63, 67);
	}

	public function getName(): string {
		return "Mesa";
	}
}