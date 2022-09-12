<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\generators\normal\populator\impl;

use TheNote\core\server\generators\normal\populator\AmountPopulator;
use TheNote\core\server\generators\normal\populator\impl\plant\Plant;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;
use function count;

class PlantPopulator extends AmountPopulator {

	/** @var Plant[] */
	private array $plants = [];

	public function addPlant(Plant $plant): void {
		$this->plants[] = $plant;
	}

	public function populateObject(ChunkManager $world, int $chunkX, int $chunkZ, Random $random): void {
		if (count($this->plants) === 0) {
			return;
		}

		$plant = $this->plants[$random->nextBoundedInt(count($this->plants))];
		if ($this->getSpawnPositionOn($world->getChunk($chunkX, $chunkZ), $random, $plant->getAllowedUnderground(), $x, $y, $z)) {
			$world->setBlockAt($chunkX * 16 + $x, $y, $chunkZ * 16 + $z, $plant->getBlock());
		}
	}
}