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
use pocketmine\world\World;
use const M_PI;

class Canyon extends Carve {

	/** @const int */
	private const CANYON_RANGE = 4;

	/** @var float[] */
	private array $sizeMap = [];

	public function carve(Chunk $populatedChunk, int $populatedChunkX, int $populatedChunkZ, int $chunkX, int $chunkZ): void {
		$x = (float)($chunkX * 16 + $this->random->nextBoundedInt(16));
		$y = (float)($this->random->nextBoundedInt($this->random->nextBoundedInt(40) + 8) + 20);
		$z = (float)($chunkZ * 16 + $this->random->nextBoundedInt(16));

		$horizontalAngle = $this->random->nextFloat() * M_PI * 2;
		$verticalAngle = ($this->random->nextFloat() - 0.5) * 0.25;

		$horizontalScale = ($this->random->nextFloat() * 2.0 + $this->random->nextFloat()) * 2.0;

		$nodeCountBound = (Canyon::CANYON_RANGE * 2 - 1) * 16;
		$nodeCount = $nodeCountBound - $this->random->nextBoundedInt($nodeCountBound);

		$this->generateCanyon($populatedChunk, $this->random->nextInt(), $chunkX, $chunkZ, $x, $y, $z, $horizontalScale, $horizontalAngle, $verticalAngle, $nodeCount);
	}

	private function generateCanyon(Chunk $chunk, int $seed, int $chunkX, int $chunkZ, float $x, float $y, float $z, float $horizontalScale, float $horizontalAngle, float $verticalAngle, int $nodeCount): void {
		$localRandom = new Random($seed);

		$baseSize = 1.0;
		for ($i = 0; $i < World::Y_MAX; ++$i) {
			if ($i == 0 || $this->random->nextBoundedInt(3) == 0) {
				$baseSize = 1.0 + $localRandom->nextFloat() * $localRandom->nextFloat();
			}

			$this->sizeMap[$i] = $baseSize ** 2;
		}

		$horizontalOffset = 0.0;
		$verticalOffset = 0.0;

		for ($node = 0; $node < $nodeCount; ++$node) {
			$horizontalSize = 1.5 + (MathHelper::getInstance()->sin((float)$node * M_PI / (float)$nodeCount) * $horizontalScale);
			$verticalSize = $horizontalSize * 3.0;

			$horizontalSize *= $localRandom->nextFloat() * 0.25 + 0.75;
			$verticalSize *= $localRandom->nextFloat() * 0.25 + 0.75;

			$cos = MathHelper::getInstance()->cos($verticalAngle);

			$x += MathHelper::getInstance()->cos($horizontalAngle) * $cos;
			$y += MathHelper::getInstance()->sin($verticalAngle);
			$z += MathHelper::getInstance()->sin($horizontalAngle) * $cos;

			$horizontalAngle += $horizontalOffset * 0.05;
			$verticalAngle = $verticalAngle * 0.7 + $verticalOffset * 0.05;

			$horizontalOffset *= 0.5;
			$verticalOffset *= 0.8;

			$horizontalOffset += ($localRandom->nextFloat() - $localRandom->nextFloat()) * $localRandom->nextFloat() * 4.0;
			$verticalOffset += ($localRandom->nextFloat() - $localRandom->nextFloat()) * $localRandom->nextFloat() * 2.0;

			if ($localRandom->nextBoundedInt(4) != 0) {
				if (!$this->canReach($chunkX, $chunkZ, $x, $z, $node, $nodeCount, $horizontalScale)) {
					return;
				}

				$this->carveSphere($chunk, $chunkX, $chunkZ, $x, $y, $z, $horizontalSize, $verticalSize);
			}
		}
	}

	protected function continue(float $modXZ, float $modY, int $y): bool {
		return ($modXZ * $this->sizeMap[$y - 1]) + ($modY ** 2) * 1.66 < 1.0;
	}

	public function canCarve(Random $random, int $chunkX, int $chunkZ): bool {
		return $random->nextFloat() <= 0.02;
	}
}