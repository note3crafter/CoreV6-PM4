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

use TheNote\core\server\generators\normal\populator\impl\plant\Plant;
use TheNote\core\server\generators\normal\populator\impl\PlantPopulator;
use pocketmine\block\VanillaBlocks;

class BadlandsPlateau extends Badlands {

	public function __construct() {
		parent::__construct();

		$deadBush = new PlantPopulator(4, 3);
		$deadBush->addPlant(new Plant(VanillaBlocks::DEAD_BUSH(), [VanillaBlocks::HARDENED_CLAY()]));

		$this->clearPopulators();
		$this->addPopulators([$deadBush]);

		$this->setElevation(84, 87);
	}

	public function getName(): string {
		return "Mesa Plateau";
	}
}