<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\generators\normal\populator;

use pocketmine\block\Block;
use pocketmine\utils\Random;
use pocketmine\world\format\Chunk;
use function array_map;
use function in_array;

abstract class Populator implements \pocketmine\world\generator\populator\Populator {

	protected function getSpawnPosition(?Chunk $chunk, Random $random, ?int &$x = null, ?int &$y = null, ?int &$z = null): bool {
		if ($chunk === null) {
			return false;
		}

		$i = 0;
		do {
			$x = $random->nextBoundedInt(16);
			$z = $random->nextBoundedInt(16);

			for ($y = 0; $y < 128; ++$y) {
				if ($chunk->getFullBlock($x, $y, $z) >> 4 == 0 && $chunk->getFullBlock($x, $y + 1, $z) >> 4 == 0) {
					return true;
				}
			}
		} while ($i++ < 4);

		return false;
	}

	/**
	 * @param Block[] $requiredUnderground
	 */
	protected function getSpawnPositionOn(?Chunk $chunk, Random $random, array $requiredUnderground = [], ?int &$x = null, ?int &$y = null, ?int &$z = null): bool {
		if ($chunk === null) {
			return false;
		}

		$requiredUnderground = array_map(fn(Block $block) => $block->getFullId(), $requiredUnderground);

		$i = 0;
		do {
			$x = $random->nextBoundedInt(16);
			$z = $random->nextBoundedInt(16);

			for ($y = 0; $y < 128; ++$y) {
				if ($chunk->getFullBlock($x, $y, $z) >> 4 == 0 && in_array($chunk->getFullBlock($x, $y - 1, $z), $requiredUnderground, true)) {
					return true;
				}
			}

		} while ($i++ < 5);

		return false;
	}
}