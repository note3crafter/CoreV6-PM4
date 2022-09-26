<?php

namespace TheNote\core\task;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\world\World;
use TheNote\core\Main;

class StopTimeTask extends Task {

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onRun(): void {
        $config =  new Config($this->plugin->getDataFolder() . Main::$setup . "Config" . ".yml", Config::YAML);

        foreach(Server::getInstance()->getWorldManager()->getWorlds() as $worlds) {
            $worlds->setTime($config->get("time"));
        }
    }
}