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
use pocketmine\block\VanillaBlocks;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;

class TallGrassPopulator extends AmountPopulator {

	private bool $allowDoubleGrass = true;

	public function populateObject(ChunkManager $world, int $chunkX, int $chunkZ, Random $random): void {
		if (!$this->getSpawnPositionOn($world->getChunk($chunkX, $chunkZ), $random, [VanillaBlocks::GRASS()], $x, $y, $z)) {
			return;
		}

		if ($this->allowDoubleGrass && $random->nextBoundedInt(5) == 0) {
			$world->setBlockAt($chunkX * 16 + $x, $y, $chunkZ * 16 + $z, VanillaBlocks::DOUBLE_TALLGRASS());
			return;
		}

		$world->setBlockAt($chunkX * 16 + $x, $y, $chunkZ * 16 + $z, VanillaBlocks::TALL_GRASS());
	}

	public function setDoubleGrassAllowed(bool $allowed = true): void {
		$this->allowDoubleGrass = $allowed;
	}
}
