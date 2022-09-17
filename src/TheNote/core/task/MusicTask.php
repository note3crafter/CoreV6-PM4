<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\task;

use pocketmine\scheduler\Task;
use TheNote\core\Main;

class MusicTask extends Task
{

	private Main $plugin;

	public function __construct(Main $main)
	{
		$this->plugin = $main;
	}

	public function onRun() :void
    {
        if (isset($this->plugin->song->sounds[$this->plugin->song->tick])) {
            $i = 0;
            foreach ($this->plugin->song->sounds[$this->plugin->song->tick] as $data) {
                $this->plugin->Play($data[0], $data[1], $i);
                $i++;
            }
        }
        $this->plugin->song->tick++;
        if ($this->plugin->song->tick > $this->plugin->song->length) {
            $this->plugin->StartNewTask();
        }
    }

    /*    protected function playTick() : void{
        foreach($this->song->getLayerHashMap() as $layer){
            $note = $layer->getNote($this->tick);
            if (is_null($note)){
                continue;
            }
            $volume = ($layer->getVolume() * 50) / 10000;
            $pitch = 2 ** (($note->getKey() - 45) / 12);
            $sound = NBSFile::MAPPING[$note->instrument] ?? NBSFile::MAPPING[NBSFile::INSTRUMENT_PIANO];
            //Create play sound packet
            $packet = new PlaySoundPacket();
            $packet->soundName = $sound;
            $packet->pitch = $pitch;
            $packet->volume = $volume;
            $pos = $this->player->getLocation()->asVector3();
            $packet->x = $pos->x;
            $packet->y = $pos->y + $this->player->getEyeHeight();
            $packet->z = $pos->z;
            $this->player->getNetworkSession()->sendDataPacket($packet);
            unset($packet, $pos, $note);
        }
    }*/
}
