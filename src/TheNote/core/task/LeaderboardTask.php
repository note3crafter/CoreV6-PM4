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

use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\world\particle\FloatingTextParticle;
use TheNote\core\Main;
use TheNote\core\utils\Manager as STM;

class LeaderboardTask extends Task
{

    private $plugin;
    private $floattext;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onRun() : void
    {

        $all = $this->plugin->getServer()->getOnlinePlayers();

        foreach ($all as $player) {
            if(!$player->isOnline()) return;

            $config = new Config($this->plugin->getDataFolder() . Main::$setup . "Config" . ".yml", Config::YAML);
			$level = $this->plugin->getServer()->getWorldManager()->getWorldByName($config->get("level"));

			$text = $this->getText($player);
            $x = $config->get("X");
            $y = $config->get("Y");
            $z = $config->get("Z");

            if ($this->plugin->anni === 1) {
                $this->plugin->anni = 2;
            } elseif ($this->plugin->anni === 2) {
                $this->plugin->anni = 1;
            }
            if ($config->get("leaderboard") == true) {
                if (!isset($this->floattext[$player->getName()])) {
                    # existiert noch nicht
                    $this->floattext[$player->getName()] = new FloatingTextParticle($text);
                    $particle = $this->floattext[$player->getName()];
                    #$packet = $particle->encode()
                    $particle->setInvisible(true);
                    $level->addParticle(new Vector3($x, $y, $z),$particle, [$player]);
                } else {
                    # is schon da
                    $particle = $this->floattext[$player->getName()];
                    $particle->setInvisible(true);
                    $level->addParticle(new Vector3($x, $y, $z), $particle, $all);
                    $this->floattext[$player->getName()] = new FloatingTextParticle($text);
                    $newparticle = $this->floattext[$player->getName()];
                    $newparticle->setInvisible(false);
                    $level->addParticle(new Vector3($x, $y, $z), $newparticle, [$player]);
                }
            }
        }
    }

    public function getText(Player $player)
    {
        $setup = new Config($this->plugin->getDataFolder() . Main::$setup . "Leaderboard.yml", Config::YAML);
        if ($this->plugin->anni === 1) {
            $text = STM::formateString($this->plugin, $player, $setup->get("leaderboard"));
        } else {
            $text = STM::formateString($this->plugin, $player, $setup->get("leaderboard"));
        }
        return $text;
    }
}