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

use TheNote\core\server\generators\normal\BiomeFactory;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\Liquid;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;
use pocketmine\world\format\Chunk;
use pocketmine\world\generator\populator\Populator;
use function count;
use function min;

class GroundCoverPopulator implements Populator
{

	public function populate(ChunkManager $world, int $chunkX, int $chunkZ, Random $random): void {
		/** @var Chunk $chunk */
		$chunk = $world->getChunk($chunkX, $chunkZ);
		$factory = BlockFactory::getInstance();
		$biomeRegistry = BiomeFactory::getInstance();
		for ($x = 0; $x < 16; ++$x) {
			for ($z = 0; $z < 16; ++$z) {
				$biome = $biomeRegistry->getBiome($chunk->getBiomeId($x, $z));
				$cover = $biome->getGroundCover();
				if (count($cover) > 0) {
					$diffY = 0;
					if (!$cover[0]->isSolid()) {
						$diffY = 1;
					}

					$startY = 127;
					for (; $startY > 0; --$startY) {
						if (!$factory->fromFullBlock($chunk->getFullBlock($x, $startY, $z))->isTransparent()) {
							break;
						}
					}
					$startY = min(127, $startY + $diffY);
					$endY = $startY - count($cover);
					for ($y = $startY; $y > $endY and $y >= 0; --$y) {
						$b = $cover[$startY - $y];
						$id = $factory->fromFullBlock($chunk->getFullBlock($x, $y, $z));
						if ($id->getId() === BlockLegacyIds::AIR and $b->isSolid()) {
							break;
						}
						if ($b->canBeFlowedInto() and $id instanceof Liquid) {
							continue;
						}

						$chunk->setFullBlock($x, $y, $z, $b->getFullId());
					}
				}
			}
		}
	}
}
