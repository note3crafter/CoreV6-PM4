<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\events;


use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\LevelChunkPacket;
use pocketmine\network\mcpe\protocol\PacketPool;
use pocketmine\network\mcpe\protocol\serializer\PacketBatch;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;
use pocketmine\Server;
use TheNote\core\Main;
use TheNote\core\task\ChunkModificationTask;
use TheNote\core\server\ModifiedChunk;
use TheNote\core\task\BlockCalculationTask;
use function array_map;

class AntiXrayEvent implements Listener {

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
	public function onDataSend(DataPacketSendEvent $event) {
		if (($batch = $event->getPackets()) instanceof PacketBatch && !($batch instanceof ModifiedChunk)) {
			$batch->decode();
			foreach (Main::getPacketsFromBatch($batch) as $packet) {
				$chunkPacket = PacketPool::getPacket($packet);
				if ($chunkPacket instanceof LevelChunkPacket) {
					$chunkPacket->decode();
                    Server::getInstance()->getAsyncPool()->submitTask(new ChunkModificationTask($event->getTargets()->getChunk($chunkPacket->getChunkX(), $chunkPacket->getChunkZ()), $event->getPlayer()));
					$event->cancel();
				}
			}
		}
	}
	public function onBreak(BlockBreakEvent $event) {
		if ($event->isCancelled()) return;
		$players = $event->getBlock()->getPosition()->getWorld()->getChunkPlayers($event->getBlock()->getPosition()->getFloorX() >> 4, $event->getBlock()->getPosition()->getFloorZ() >> 4);
        $blocks = Main::getInvolvedBlocks([$event->getBlock()->getPosition()->asVector3()]);
		$event->getPlayer()->getWorld()->sendblock($players, $blocks, UpdateBlockPacket::FLAG_NEIGHBORS);
	}

    public function onExplode(EntityExplodeEvent $event) {
        if ($event->isCancelled()) return;
        Server::getInstance()->getAsyncPool()->submitTask(new BlockCalculationTask(array_map(function($block) {
            return $block->getPosition()->asVector3();
        }, 		$event->getBlockList()), $event->getEntity()->getWorld()->getFolderName()));
    }
}
