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
}
