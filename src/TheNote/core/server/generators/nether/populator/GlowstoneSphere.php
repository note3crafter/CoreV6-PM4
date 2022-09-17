<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\generators\nether\populator;

use pocketmine\block\VanillaBlocks;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;

class GlowstoneSphere implements \pocketmine\world\generator\populator\Populator
{
	public const SPHERE_RADIUS = 3;

	public function populate(ChunkManager $world, int $chunkX, int $chunkZ, Random $random): void {
		$world->getChunk($chunkX, $chunkZ);
		if ($random->nextRange(0, 10) !== 0) return;

		$x = $random->nextRange($chunkX << 4, ($chunkX << 4) + 15);
		$z = $random->nextRange($chunkZ << 4, ($chunkZ << 4) + 15);

		$sphereY = 0;

		for ($y = 0; $y < 127; $y++) {
			if ($world->getBlockAt($x, $y, $z)->isSameType(VanillaBlocks::AIR())) {
				$sphereY = $y;
			}
		}

		if ($sphereY < 80) {
			return;
		}

		$this->placeGlowstoneSphere($world, $random, new Vector3($x, $sphereY - $random->nextRange(2, 4), $z));
	}

	public function placeGlowStoneSphere(ChunkManager $world, Random $random, Vector3 $position): void {
		for ($x = $position->getX() - $this->getRandomRadius($random); $x < $position->getX() + $this->getRandomRadius($random); $x++) {
			$xsqr = ($position->getX() - $x) * ($position->getX() - $x);
			for ($y = $position->getY() - $this->getRandomRadius($random); $y < $position->getY() + $this->getRandomRadius($random); $y++) {
				$ysqr = ($position->getY() - $y) * ($position->getY() - $y);
				for ($z = $position->getZ() - $this->getRandomRadius($random); $z < $position->getZ() + $this->getRandomRadius($random); $z++) {
					$zsqr = ($position->getZ() - $z) * ($position->getZ() - $z);
					if (($xsqr + $ysqr + $zsqr) < (pow(2, $this->getRandomRadius($random)))) {
						if ($random->nextRange(0, 4) !== 0) {
							$world->setBlockAt($x, $y, $z, VanillaBlocks::GLOWSTONE());
						}
					}
				}
			}
		}
	}

	public function getRandomRadius(Random $random): int {
		return $random->nextRange(self::SPHERE_RADIUS, self::SPHERE_RADIUS + 2);
	}
}