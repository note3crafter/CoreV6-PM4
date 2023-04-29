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

use TheNote\core\server\generators\normal\object\Tree;
use TheNote\core\server\generators\normal\populator\AmountPopulator;
use pocketmine\block\utils\TreeType;
use pocketmine\block\VanillaBlocks;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;

class TreePopulator extends AmountPopulator {

	private ?TreeType $treeType;

	private bool $vines;

	private bool $high;

	public function __construct(int $baseAmount, int $randomAmount, int $spawnPercentage = 100, ?TreeType $treeType = null, bool $vines = false, bool $high = false) {
		$this->treeType = $treeType;
		$this->vines = $vines;
		$this->high = $high;

		parent::__construct($baseAmount, $randomAmount, $spawnPercentage);
	}

	public function populateObject(ChunkManager $world, int $chunkX, int $chunkZ, Random $random): void {
		if (!$this->getSpawnPositionOn($world->getChunk($chunkX, $chunkZ), $random, [VanillaBlocks::GRASS(), VanillaBlocks::MYCELIUM()], $x, $y, $z)) {
			return;
		}

		Tree::growTree($world, $chunkX * 16 + $x, $y, $chunkZ * 16 + $z, $random, $this->treeType, $this->vines, $this->high);
	}
}
