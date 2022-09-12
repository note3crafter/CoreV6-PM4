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

class CactusPopulator extends AmountPopulator {

	public function populateObject(ChunkManager $world, int $chunkX, int $chunkZ, Random $random): void {
		if (!$this->getSpawnPositionOn($world->getChunk($chunkX, $chunkZ), $random, [VanillaBlocks::SAND(), VanillaBlocks::RED_SAND()], $x, $y, $z)) {
			return;
		}

		$x += $chunkX * 16;
		$z += $chunkZ * 16;

		if (
			!$world->getBlockAt($x + 1, $y, $z)->isSameType(VanillaBlocks::AIR()) ||
			!$world->getBlockAt($x, $y, $z + 1)->isSameType(VanillaBlocks::AIR()) ||
			!$world->getBlockAt($x - 1, $y, $z)->isSameType(VanillaBlocks::AIR()) ||
			!$world->getBlockAt($x, $y, $z - 1)->isSameType(VanillaBlocks::AIR())
		) {
			return;
		}

		$size = $random->nextBoundedInt(4);
		for ($i = 0; $i < $size; ++$i) {
			$world->setBlockAt($x, $y + $i, $z, VanillaBlocks::CACTUS());
		}
	}
}