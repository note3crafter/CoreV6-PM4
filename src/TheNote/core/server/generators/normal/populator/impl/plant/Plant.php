<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\generators\normal\populator\impl\plant;

use pocketmine\block\Block;
use pocketmine\block\VanillaBlocks;

class Plant {

	/**
	 * @param Block[] $allowedUnderground If empty, Grass will be used as a default option
	 */
	public function __construct(
		private Block $block,
		private array $allowedUnderground = []
	) {
		if (empty($this->allowedUnderground)) {
			$this->allowedUnderground = [VanillaBlocks::GRASS()];
		}
	}

	public function getBlock(): Block {
		return $this->block;
	}

	/**
	 * @return Block[]
	 */
	public function getAllowedUnderground(): array {
		return $this->allowedUnderground;
	}
}