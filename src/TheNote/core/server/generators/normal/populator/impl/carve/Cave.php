<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\generators\normal\populator\impl\carve;

use TheNote\core\utils\MathHelper;
use pocketmine\utils\Random;
use pocketmine\world\format\Chunk;
use function floor;
use function max;
use const M_PI;
use const M_PI_2;

class Cave extends Carve {

	/** @const int */
	private const CAVE_RANGE = 4;
	/** @const int */
	private const CAVE_BOUND = 10;
	/** @const float */
	private const CAVE_SCALE = 1.0;
	/** @const float */
	private const CAVE_TUNNEL_COUNT = 1.0;

	public function carve(Chunk $populatedChunk, int $populatedChunkX, int $populatedChunkZ, int $chunkX, int $chunkZ): void {
		$i = (Cave::CAVE_RANGE * 2 - 1) * 16;
		$j = $this->random->nextBoundedInt($this->random->nextBoundedInt($this->random->nextBoundedInt(Cave::CAVE_BOUND) + 1) + 1);

		for ($k = 0; $k < $j; ++$k) {
			$x = (float)(($chunkX * 16) + $this->random->nextBoundedInt(16));
			$y = (float)$this->random->nextBoundedInt($this->random->nextBoundedInt(120) + 8);
			$z = (float)(($chunkZ * 16) + $this->random->nextBoundedInt(16));

			$tunnelCount = Cave::CAVE_TUNNEL_COUNT;
			if ($this->random->nextBoundedInt(4) == 0) {
				$this->generateRoom($populatedChunk, $populatedChunkX, $populatedChunkZ, $x, $y, $z, 1.0 + ($this->random->nextFloat() * 6.0));

				$tunnelCount += $this->random->nextBoundedInt(4);
			}

			for ($tunnel = 0; $tunnel < $tunnelCount; ++$tunnel) {
				$this->generateTunnel($populatedChunk, $populatedChunkX, $populatedChunkZ, $this->random->nextInt(), $chunkX, $chunkZ, $x, $y, $z, $this->getRandomScale($this->random), $this->random->nextFloat() * M_PI * 2.0, ($this->random->nextFloat() - 0.5) * 0.25, 0, $i - $this->random->nextBoundedInt((int)floor($i / 4)), Cave::CAVE_SCALE);
			}
		}
	}

	private function generateTunnel(Chunk $chunk, int $populatedChunkX, int $populatedChunkZ, int $seed, int $chunkX, int $chunkZ, float $x, float $y, float $z, float $horizontalScale, float $horizontalAngle, float $verticalAngle, int $node, int $nodeCount, float $scale): void {
		$localRandom = new Random($seed);

		$randomStartingNode = $localRandom->nextBoundedInt((int)max(1, floor($nodeCount * 0.5))) + (int)floor($nodeCount * 0.25);
		$flag = $localRandom->nextBoundedInt(6) == 0;

		$horizontalOffset = 0.0;
		$verticalOffset = 0.0;

		for (; $node < $nodeCount; ++$node) {
			$horizontalSize = 1.5 + (MathHelper::getInstance()->sin(M_PI * $node / (float)$nodeCount) * $horizontalScale);
			$verticalSize = $horizontalSize * $scale;

			$f2 = MathHelper::getInstance()->cos($verticalAngle);

			$x += MathHelper::getInstance()->cos($horizontalAngle) * $f2;
			$y += MathHelper::getInstance()->sin($verticalAngle);
			$z += MathHelper::getInstance()->sin($horizontalAngle) * $f2;

			$verticalAngle *= ($flag ? 0.92 : 0.7);

			$horizontalAngle += $horizontalOffset * 0.1;
			$verticalAngle += $verticalOffset * 0.1;

			$horizontalOffset *= 0.75;
			$verticalOffset *= 0.9;

			$horizontalOffset += ($localRandom->nextFloat() - $localRandom->nextFloat()) * $localRandom->nextFloat() * 4.0;
			$verticalOffset += ($localRandom->nextFloat() - $localRandom->nextFloat()) * $localRandom->nextFloat() * 2.0;

			if ($node == $randomStartingNode && $horizontalScale > 1.0) {
				$this->generateTunnel($chunk, $populatedChunkX, $populatedChunkZ, $localRandom->nextInt(), $chunkX, $chunkZ, $x, $y, $z, $localRandom->nextFloat() * 0.5 + 0.5, $horizontalAngle - M_PI_2, $verticalAngle / 3.0, $node, $nodeCount, 1.0);
				$this->generateTunnel($chunk, $populatedChunkX, $populatedChunkZ, $localRandom->nextInt(), $chunkX, $chunkZ, $x, $y, $z, $localRandom->nextFloat() * 0.5 + 0.5, $horizontalAngle + M_PI_2, $verticalAngle / 3.0, $node, $nodeCount, 1.0);
				return;
			}

			if ($localRandom->nextBoundedInt(4) != 0) {
				if (!$this->canReach($populatedChunkX, $populatedChunkZ, $x, $z, $node, $nodeCount, $horizontalScale)) {
					return;
				}

				$this->carveSphere($chunk, $populatedChunkX, $populatedChunkZ, $x, $y, $z, $horizontalSize, $verticalSize);
			}
		}
	}

	private function generateRoom(Chunk $chunk, int $populatedChunkX, int $populatedChunkZ, float $x, float $y, float $z, float $roomSize): void {
		$this->carveSphere($chunk, $populatedChunkX, $populatedChunkZ, $x + 1.0, $y, $z, $horizontalSize = 1.5 + $roomSize, $horizontalSize * 0.5);
	}

	private function getRandomScale(Random $random): float {
		$thickness = $random->nextFloat() * 2.0 + $random->nextFloat();
		if ($random->nextBoundedInt(10) == 0) {
			$thickness *= $random->nextFloat() * $random->nextFloat() * 3.0 + 1.0;
		}

		return $thickness;
	}

	protected function continue(float $modXZ, float $modY, int $y): bool {
		return $modY > -0.7 && $modXZ + $modY ** 2 < 1.0;
	}

	public function canCarve(Random $random, int $chunkX, int $chunkZ): bool {
		return $random->nextFloat() <= 0.14285714;
	}
}