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

class Forest extends GrassyBiome {

	public function __construct() {
		parent::__construct(0.7, 0.8);

		$mushrooms = new PlantPopulator(2, 2, 95);
		$mushrooms->addPlant(new Plant(VanillaBlocks::RED_MUSHROOM()));
		$mushrooms->addPlant(new Plant(VanillaBlocks::BROWN_MUSHROOM()));

		$flowers = new PlantPopulator(6, 7, 80);
		$flowers->addPlant(new Plant(VanillaBlocks::DANDELION()));
		$flowers->addPlant(new Plant(VanillaBlocks::POPPY()));

		$roses = new PlantPopulator(5, 4, 80);
		$roses->addPlant(new Plant(VanillaBlocks::LILAC()));

		$peonys = new PlantPopulator(5, 4, 80);
		$peonys->addPlant(new Plant(VanillaBlocks::PEONY()));

		$oak = new TreePopulator(3, 3);
		$birch = new TreePopulator(3, 3, 100, TreeType::OAK());

		$grass = new TallGrassPopulator(56, 30);

		$this->addPopulators([$oak, $birch, $flowers, $peonys, $roses, $mushrooms, $grass]);

		$this->setElevation(63, 70);
	}

	public function getName(): string {
		return "Forest";
	}
}