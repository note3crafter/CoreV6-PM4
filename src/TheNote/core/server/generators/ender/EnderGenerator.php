<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\generators\ender;

use pocketmine\block\BlockLegacyIds;
use pocketmine\math\Vector3 as Vector3;
use pocketmine\world\ChunkManager;
use pocketmine\world\generator\Generator;
use pocketmine\world\generator\noise\Simplex;
use TheNote\core\server\generators\ender\populator\EnderPilar;

class EnderGenerator extends Generator {

	protected ChunkManager $world;
	protected $random;
	private Simplex $noiseBase;
	private array $populators = [];
	private array $generationPopulators = [];
	private int $emptyHeight = 32;
	private float $emptyAmplitude = 1;
	private float $density = 0.6;

	public function __construct(int $seed, string $preset) {
		parent::__construct($seed, $preset);

		$this->random->setSeed($this->seed);
		$this->noiseBase = new Simplex($this->random, 4, 1 / 4, 1 / 64);
		$this->random->setSeed($this->seed);
		$pilar = new EnderPilar;
		$pilar->setBaseAmount(0);
		$pilar->setRandomAmount(0);
		$this->populators[] = $pilar;
	}

	public function generateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void {
		$this->random->setSeed(0xa6fe78dc ^ ($chunkX << 8) ^ $chunkZ ^ $this->seed);

		$chunk = $this->world->getChunk($chunkX, $chunkZ);
		$noise = $this->noiseBase->getFastNoise3D(16, 128, 16, 4, 8, 4, $chunkX * 16, 0, $chunkZ * 16);

		for ($x = 0; $x < 16; ++$x) {
			for ($z = 0; $z < 16; ++$z) {
				// 9 = biome end
				$chunk->setBiomeId($x, $z, 9);
				for ($y = 0; $y < 128; ++$y) {
					$noiseValue = (abs($this->emptyHeight - $y) / $this->emptyHeight) * $this->emptyAmplitude - $noise[$x][$z][$y];
					$noiseValue -= 1 - $this->density;
					$distance = new Vector3(0, 64, 0);
					$distance = $distance->distance(new Vector3($chunkX * 16 + $x, ($y / 1.3), $chunkZ * 16 + $z));
					if ($noiseValue < 0 && $distance < 100 or $noiseValue < -0.2 && $distance > 400) {
						$chunk->setFullBlock($x, $y, $z, BlockLegacyIds::END_STONE << 4);
					}
				}
			}
		}
		foreach ($this->generationPopulators as $populator) {
			$populator->populate($this->world, $chunkX, $chunkZ, $this->random);
		}
	}

	public function populateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void {
		$this->random->setSeed(0xa6fe78dc ^ ($chunkX << 8) ^ $chunkZ ^ $this->seed);
		foreach ($this->populators as $populator) {
			$populator->populate($this->world, $chunkX, $chunkZ, $this->random);
		}
	}
}