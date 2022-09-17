<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\generators\void;

use pocketmine\block\BlockLegacyIds;
use pocketmine\world\ChunkManager;

class VoidGenerator extends \pocketmine\world\generator\Generator
{

	public function __construct(int $seed, string $preset) {
		parent::__construct($seed, $preset);
	}

	public function generateChunk(\pocketmine\world\ChunkManager $world, int $chunkX, int $chunkZ): void {
		$chunk = $world->getChunk($chunkX, $chunkZ);

		if ($chunkX == 16 && $chunkZ == 16) {
			$chunk->setFullBlock(0, 64, 0, BlockLegacyIds::GRASS << 4);
		}
	}

	public function populateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void {
	}
}