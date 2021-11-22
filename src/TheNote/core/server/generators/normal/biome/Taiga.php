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

use TheNote\core\server\generators\normal\biome\types\GrassyBiome;
use TheNote\core\server\generators\normal\populator\impl\plant\Plant;
use TheNote\core\server\generators\normal\populator\impl\PlantPopulator;
use TheNote\core\server\generators\normal\populator\impl\TallGrassPopulator;
use TheNote\core\server\generators\normal\populator\impl\TreePopulator;
use pocketmine\block\utils\TreeType;
use pocketmine\block\VanillaBlocks;

class Taiga extends GrassyBiome {

	public function __construct() {
		parent::__construct(0.25, 0.8);

		$mushrooms = new PlantPopulator(2, 2, 95);
		$mushrooms->addPlant(new Plant(VanillaBlocks::RED_MUSHROOM()));
		$mushrooms->addPlant(new Plant(VanillaBlocks::BROWN_MUSHROOM()));

		$flowers = new PlantPopulator(6, 7, 80);
		$flowers->addPlant(new Plant(VanillaBlocks::DANDELION()));
		$flowers->addPlant(new Plant(VanillaBlocks::POPPY()));

		$spruce = new TreePopulator(3, 2, 100, TreeType::SPRUCE());

		$tallGrass = new TallGrassPopulator(56, 12);

		$this->addPopulators([$mushrooms, $flowers, $spruce, $tallGrass]);
		$this->addPopulator($spruce);
		$this->addPopulator($flowers);
		$this->addPopulator($mushrooms);

		$this->setElevation(70, 79);
	}

	public function getName(): string {
		return "Taiga";
	}
}