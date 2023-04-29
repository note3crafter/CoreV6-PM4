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

use TheNote\core\server\generators\normal\biome\types\Biome;
use TheNote\core\server\generators\normal\populator\impl\plant\Plant;
use TheNote\core\server\generators\normal\populator\impl\PlantPopulator;
use pocketmine\block\VanillaBlocks;

class MushroomIsland extends Biome {

	public function __construct() {
		parent::__construct(0.9, 1);
		$this->setGroundCover([
			VanillaBlocks::MYCELIUM(),
			VanillaBlocks::DIRT(),
			VanillaBlocks::DIRT(),
			VanillaBlocks::DIRT(),
			VanillaBlocks::DIRT()
		]);

		$mushrooms = new PlantPopulator(2, 2, 95);
		$mushrooms->addPlant(new Plant(VanillaBlocks::RED_MUSHROOM(), [VanillaBlocks::MYCELIUM()]));
		$mushrooms->addPlant(new Plant(VanillaBlocks::BROWN_MUSHROOM(), [VanillaBlocks::MYCELIUM()]));

//        $this->addPopulators([$mushrooms, new TreePopulator(1, 1, 100, Tree::MUSHROOM)]); // TODO

		$this->setElevation(64, 74);
	}

	public function getName(): string {
		return "Mushroom Island";
	}
}