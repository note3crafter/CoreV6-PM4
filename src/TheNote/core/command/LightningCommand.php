<?php

namespace TheNote\core\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\object\PrimedTNT;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\utils\Random;
use pocketmine\world\Position;
use pocketmine\world\World;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class LightningCommand extends Command
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("top", $config->get("prefix") . $api->getLang("topprefix"), "/top");
        $this->setPermission("core.command.top");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool{
        $api = new BaseAPI();
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . $api->getLang("commandingame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . $api->getLang("nopermission"));
            return false;
        }
        if((!isset($args[0]) && !$sender instanceof Player) || count($args) > 2){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $player = $sender;
        if(isset($args[0]) && !($player = $this->getAPI()->getPlayer($args[0]))){
            $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
            return false;
        }
        $pos = isset($args[0]) ? $player : $player->getTargetBlock(100);
        $damage = $args[1] ?? 0;
        $this->getAPI()->strikeLightning($pos, $damage);
        $sender->sendMessage(TextFormat::YELLOW . "Lightning launched!");
        return true;
    }
    public function strikeLightning(Position $pos, int $damage = 0): void{
        $pk = $this->lightning($pos);
        foreach($pos->getLevel()->getPlayers() as $p){
            $p->dataPacket($pk);
        }
        if(!$pos instanceof Entity and !($pos = $this->createTNT($pos, null, false))){
            return;
        }
        foreach($pos->getLevel()->getNearbyEntities(new AxisAlignedBB($pos->getFloorX() - ($radius = 5), $pos->getFloorY() - $radius, $pos->getFloorZ() - $radius, $pos->getFloorX() + $radius, $pos->getFloorY() + $radius, $pos->getFloorZ() + $radius), $pos) as $e){
            $e->attack(new EntityDamageEvent($pos, EntityDamageEvent::CAUSE_MAGIC, $damage));
        }
    }

    /** @var null|AddEntityPacket */
    private $lightningPacket = null;

    /**
     * @param Vector3 $pos
     *
     * @return AddEntityPacket
     */
    protected function lightning(Vector3 $pos): AddEntityPacket{
        if($this->lightningPacket === null){
            $pk = new AddEntityPacket();
            $pk->type = 93;
            $pk->entityRuntimeId = Entity::$entityCount++;
            $pk->metadata = [];
            $motion = new Vector3(0, 0, 0);
            $pk->motion = $motion;
            $this->lightningPacket = $pk;
        }
        $this->lightningPacket->position = $pos;

        return $this->lightningPacket;
    }
    public function createEntity(string $type, Vector3 $pos, World $level = null, CompoundTag $nbt = null): ?Entity{
        if($level === null){
            if($pos instanceof Position){
                $level = $pos->getWorld();
            }else{
                return null;
            }
        }
        if($nbt === null){
            $nbt = new CompoundTag("EssPE", [
                "Pos" => new ListTag("Pos", [
                    new DoubleTag("x", $pos->getX()),
                    new DoubleTag("y", $pos->getY()).
                    new DoubleTag("z", $pos->getZ())
                ])
            ]);
        }

        return Entity::createEntity($type, $level, $nbt);
    }

    /**
     * @param Vector3|Position $pos
     * @param null|Level $level
     * @param bool $spawn
     *
     * @return null|PrimedTNT
     */
    public function createTNT(Vector3 $pos, Level $level = null, $spawn = true): ?PrimedTNT{
        $mot = (new Random())->nextSignedFloat() * M_PI * 2;
        $entity = $this->createEntity("PrimedTNT", $pos, $level, new CompoundTag("EssPE", [
            "Pos" => new ListTag("Pos", [
                new DoubleTag("", $pos->getFloorX() + 0.5),
                new DoubleTag("", $pos->getFloorY()),
                new DoubleTag("", $pos->getFloorZ() + 0.5)
            ]),
            "Motion" => new ListTag("Motion", [
                new DoubleTag("", -sin($mot) * 0.02),
                new DoubleTag("", 0.2),
                new DoubleTag("", -cos($mot) * 0.02)
            ]),
            "Rotation" => new ListTag("Rotation", [
                new FloatTag("", 0),
                new FloatTag("", 0)
            ]),
            "Fuse" => new ByteTag("Fuse", 80),
        ]));
        if($spawn){
            $entity->spawnToAll();
        }
        if($entity instanceof PrimedTNT){
            return $entity;
        }
        return null;
    }

}