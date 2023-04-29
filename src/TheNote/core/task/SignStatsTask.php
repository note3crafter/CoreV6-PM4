<?php

namespace TheNote\core\task;

use pocketmine\block\BaseSign;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\tile\TileFactory;
use pocketmine\block\utils\SignText;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\world\format\Chunk;
use pocketmine\world\World;
use pocketmine\world\WorldManager;
use TheNote\core\Main;

class SignStatsTask extends Task
{

    public $prefix = "§f[§cSchilderManager§f] ";
    public $uhc = "§f[§dUHc§f-§aMeetup§f] ";
    public $sw = "§f[§4Sky§fWars] ";
    public $survival = "§f[§6Survival§f] ";
    public $mlg = "§f[§9M§fL§cG§f-Rush] ";
    public $kreativ = "§f[§eKreativ§f] ";
    public $clanwars = "§f[§4Clan§fWars§f] ";
    public $combo = "§f[§eCombo§f] ";
    public $bw = "§f[§4Bed§fWars] ";
    public int $load = 1;


    public function __construct(Main $plugin)
    {
      $this->plugin = $plugin;
    }

    public function onRun(): void
    {
        $s = $this->plugin->getServer();
        $m = $s->getWorldManager()->getDefaultWorld();

        if ($this->load === 1) {
            $this->plugin->load = 2;
        } else if ($this->load === 2) {
            $this->plugin->load = 3;
        } else if ($this->load === 3) {
            $this->plugin->load = 1;
        }
        //$chunks = $this->plugin->getServer()->getWorldManager()->getWorld()->getChunk();
        $block = $this->plugin->getServer()->getWorldManager()->getWorld()->getBlock();
        switch ($block->getID()) {
            case BlockLegacyIds::SIGN_POST:
            case BlockLegacyIds::WALL_SIGN:

                $m->loadChunk((int)$block->getPosition()->getX(), (int)$block->getPosition()->getZ());
                $text = $block->getText();
                if ($text[0] === "TEST") {
                    new SignText([
                        0 => "§f[§dTEST§f]",
                        1 => "§f[§dTEST§f]",
                        2 => "Beitreten",
                        3 => "NICX"

                    ]);
                }
                /*else if ($text[0] === $this->plugin->sw)
                    if ($text[1] === "§f[§4Sky§fWars]") {
                        $sw = new Config("/home/CC/SW/plugin_data/SkyWars/config.yml");
                        if ($sw->get("ingame") === true) {
                            $t->setText($this->plugin->sw,
                                "§f[§4Sky§fWars]",
                                "§cIngame",
                                "§f[§e" . $sw->get("players") . "§f/§e16§f]"
                            );
                        } else {
                            if ($sw->get("reset") === true) {
                                if ($this->plugin->load === 1) {
                                    $t->setText($this->plugin->sw,
                                        "§f[§4Sky§fWars]",
                                        "§cLade",
                                        "§dOoo"
                                    );
                                } else if ($this->plugin->load === 2) {

                                    $t->setText($this->plugin->sw,
                                        "§f[§4Sky§fWars]",
                                        "§cLade",
                                        "§doOo"
                                    );
                                } else if ($this->plugin->load === 3) {
                                    $t->setText($this->plugin->sw,
                                        "§f[§4Sky§fWars]",
                                        "§cLade",
                                        "§dooO"
                                    );
                                }
                            } else {
                                if ($sw->get("players") === 16) {

                                    $t->setText($this->plugin->sw,
                                        "§f[§4Sky§fWars]",
                                        "§5Voll",
                                        "§f[§e" . $sw->get("players") . "§f/§e16§f]"
                                    );
                                } else {
                                    $t->setText($this->plugin->sw,
                                        "§f[§4Sky§fWars]",
                                        "§aBeitreten",
                                        "§f[§e" . $sw->get("players") . "§f/§e16§f]"
                                    );
                                }
                            }
                        }
                    }*/
            }

    }
}