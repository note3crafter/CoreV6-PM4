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

use pocketmine\block\Block;
use pocketmine\item\ItemIds;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;
use TheNote\core\server\generators\normal\populator\Populator;

class SoulSand extends Populator {

    public const MAX_Y = 40;

    public function populate(ChunkManager $level, int $chunkX, int $chunkZ, Random $random) {
        $chunk = $level->getChunk($chunkX, $chunkZ);
        if($random->nextRange(0, 6) !== 0) return;

        $x = $random->nextRange($chunkX << 4 , ($chunkX << 4) + 15);
        $z = $random->nextRange($chunkZ << 4, ($chunkZ << 4) + 15);

        $sphereY = 0;

        for($y = 45; $y > 0; $y--) {
            if($level->getBlockAt($x, $y, $z) == 0) {
                $sphereY = $y;
            }
        }

        if($sphereY-3 < 2) {
            return;
        }

        if($level->getBlockAt($x, $sphereY-3, $z) != ItemIds::NETHERRACK) {
            return;
        }

        $this->placeSoulsandSphere($level, $random, new Vector3($x, $sphereY - $random->nextRange(2, 4), $z));
    }

    public function placeSoulsandSphere(ChunkManager $level, Random $random, Vector3 $position) {
        $radiusX = $random->nextRange(8, 15);
        $radiusZ = $random->nextRange(8, 15);
        $radiusY = $random->nextRange(5, 8);
        for($x = $position->getX() - $radiusX; $x < $position->getX() + $radiusX; $x++) {
            $xsqr = ($position->getX()-$x) * ($position->getX()-$x);
            for($y = $position->getY() - $radiusY; $y < $position->getY() + $radiusY; $y++) {
                $ysqr = ($position->getY()-$y) * ($position->getY()-$y);
                for($z = $position->getZ() - $radiusZ; $z < $position->getZ() + $radiusZ; $z++) {
                    $zsqr = ($position->getZ()-$z) * ($position->getZ()-$z);
                    if(($xsqr + $ysqr + $zsqr) < (pow(2, $random->nextRange(3, 6)))) {
                        if($level->getBlockAt($x, $y, $z) == ItemIds::NETHERRACK) {
                            $level->setBlockAt($x, $y, $z, Block::get(ItemIds::SOUL_SAND));
                            if($random->nextRange(0, 3) == 3 && $level->getBlockAt($x, $y+1, $z) == ItemIds::AIR) {
                                $level->setBlockAt($x, $y+1, $z, Block::get(ItemIds::NETHER_WART_PLANT));
                            }
                        }
                    }
                }
            }
        }
    }
}