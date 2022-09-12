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

use TheNote\core\server\generators\normal\populator\impl\carve\Canyon;
use TheNote\core\server\generators\normal\populator\impl\carve\Carve;
use TheNote\core\server\generators\normal\populator\impl\carve\Cave;
use TheNote\core\server\generators\normal\populator\Populator;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;
use pocketmine\world\format\Chunk;

class CarvePopulator extends Populator {

	/** @const int */
	public const CHECK_AREA_SIZE = 6; // originally 8

	private int $seed;

	private Random $random;
	/** @var Carve[] */
	private array $carves = [];

	public function __construct(int $seed) {
		$this->seed = $seed;
		$this->random = new Random(0);

		$this->carves[] = new Canyon($this->random);
		$this->carves[] = new Cave($this->random);
	}

	public function populate(ChunkManager $world, int $chunkX, int $chunkZ, Random $random): void {
		$localRandom = new Random($this->seed);
		$xSeed = $localRandom->nextInt();
		$zSeed = $localRandom->nextInt();

		/** @var Chunk $chunk */
		$chunk = $world->getChunk($chunkX, $chunkZ);

		$minX = $chunkX - CarvePopulator::CHECK_AREA_SIZE;
		$maxX = $chunkX + CarvePopulator::CHECK_AREA_SIZE;
		$minZ = $chunkX - CarvePopulator::CHECK_AREA_SIZE;
		$maxZ = $chunkZ + CarvePopulator::CHECK_AREA_SIZE;

		for ($x = $minX; $x <= $maxX; ++$x) {
			$randomX = $xSeed * $x;
			for ($z = $minZ; $z <= $maxZ; ++$z) {
				$randomZ = $zSeed * $z;

				$seed = $randomX ^ $randomZ ^ $this->seed;
				foreach ($this->carves as $carve) {
					$this->random->setSeed($seed);
					if ($carve->canCarve($this->random, $chunkX, $chunkZ)) {
						$carve->carve($chunk, $chunkX, $chunkZ, $x, $z);
					}
				}
			}
		}
	}
}