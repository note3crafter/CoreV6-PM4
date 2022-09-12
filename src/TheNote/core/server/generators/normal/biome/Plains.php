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
use TheNote\core\server\generators\normal\populator\impl\LakePopulator;
use TheNote\core\server\generators\normal\populator\impl\plant\Plant;
use TheNote\core\server\generators\normal\populator\impl\PlantPopulator;
use TheNote\core\server\generators\normal\populator\impl\TallGrassPopulator;
use TheNote\core\server\generators\normal\populator\impl\TreePopulator;
use pocketmine\block\VanillaBlocks;

class Plains extends GrassyBiome {

	public function __construct() {
		parent::__construct(0.8, 0.4);

		$flowers = new PlantPopulator(9, 7, 85);
		$flowers->addPlant(new Plant(VanillaBlocks::DANDELION()));
		$flowers->addPlant(new Plant(VanillaBlocks::POPPY()));

		$daisy = new PlantPopulator(9, 7, 85);
		$daisy->addPlant(new Plant(VanillaBlocks::OXEYE_DAISY()));

		$bluet = new PlantPopulator(9, 7, 85);
		$bluet->addPlant(new Plant(VanillaBlocks::AZURE_BLUET()));

		$tulips = new PlantPopulator(9, 7, 85);
		$tulips->addPlant(new Plant(VanillaBlocks::PINK_TULIP()));
		$tulips->addPlant(new Plant(VanillaBlocks::ORANGE_TULIP()));

		$tree = new TreePopulator(2, 1, 85);
		$lake = new LakePopulator();
		$tallGrass = new TallGrassPopulator(89, 26);

		$this->addPopulators([$lake, $flowers, $daisy, $bluet, $tulips, $tree, $tallGrass]);

		$this->setElevation(62, 66);
	}

	public function getName(): string {
		return "Plains";
	}
}