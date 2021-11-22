<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\generators\ender\populator;

use TheNote\core\utils\MathHelper;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\VanillaBlocks;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;
use pocketmine\world\generator\populator\Populator;
use function deg2rad;
use const M_PI;

class EnderPilar implements Populator {

	private ChunkManager $world;

	private int $randomAmount;

	private int $baseAmount;

	public function setRandomAmount(int $amount): void {
		$this->randomAmount = $amount;
	}

	public function setBaseAmount(int $amount): void {
		$this->baseAmount = $amount;
	}

	public function populate(ChunkManager $world, int $chunkX, int $chunkZ, Random $random): void {
		if ($random->nextRange(0, 100) < 10) {
			$this->world = $world;
			$amount = $random->nextRange(0, $this->randomAmount + 1) + $this->baseAmount;
			for ($i = 0; $i < $amount; ++$i) {
				$x = $random->nextRange($chunkX * 16, $chunkX * 16 + 15);
				$z = $random->nextRange($chunkZ * 16, $chunkZ * 16 + 15);
				$y = $this->getHighestWorkableBlock($x, $z);
				if ($this->world->getBlockAt($x, $y, $z)->getId() == BlockLegacyIds::END_STONE) {
					$height = $random->nextRange(28, 50);
					for ($ny = $y; $ny < $y + $height; $ny++) {
						for ($r = 0.5; $r < 5; $r += 0.5) {
							$nd = 180 / (M_PI * $r);
							for ($d = 0; $d < 360; $d += $nd) {
								$world->setBlockAt((int)($x + (MathHelper::getInstance()->cos(deg2rad($d)) * $r)), $ny, (int)($z + (MathHelper::getInstance()->sin(deg2rad($d)) * $r)), VanillaBlocks::OBSIDIAN());
							}
						}
					}
				}
			}
		}
	}

	private function getHighestWorkableBlock(int $x, int $z): int {
		for ($y = 127; $y >= 0; --$y) {
			$b = $this->world->getBlockAt($x, $y, $z)->getId();
			if ($b == BlockLegacyIds::END_STONE) {
				break;
			}
		}
		return $y === 0 ? -1 : $y;
	}
}