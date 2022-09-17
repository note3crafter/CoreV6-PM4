<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//


namespace TheNote\core\entity\projectile;

use pocketmine\block\Block;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\entity\Entity;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Location;
use pocketmine\entity\projectile\Projectile;
use pocketmine\event\entity\EntityItemPickupEvent;
use pocketmine\math\RayTraceResult;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use TheNote\core\item\Trident as TridentItem;
use TheNote\core\sounds\TridentHitGroundSound;
use TheNote\core\sounds\TridentHitSound;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\network\mcpe\protocol\TakeItemActorPacket;

class TridentEntity extends Projectile
{


    public static function getNetworkTypeId(): string
    {
        return EntityLegacyIds::THROWN_TRIDENT;
    }

    public const PICKUP_NONE = 0;
    public const PICKUP_ANY = 1;
    public const PICKUP_CREATIVE = 2;

    private const TAG_PICKUP = "pickup"; //TAG_Byte

    protected $item;

    protected $gravity = 0.05;
    protected $drag = 0.01;
    protected $damage = 8.0;
    protected $pickupMode = self::PICKUP_ANY;
    protected $canHitEntity = true;

    public function __construct(Location $location, TridentItem $item, ?Entity $shootingEntity, ?CompoundTag $nbt = null)
    {
        if ($item->isNull()) {
            throw new \InvalidArgumentException("Trident must have a count of at least 1");
        }
        $this->setItem($item);
        parent::__construct($location, $shootingEntity, $nbt);
    }

    protected function getInitialSizeInfo(): EntitySizeInfo
    {
        return new EntitySizeInfo(0.25, 0.25);
    }

    protected function initEntity(CompoundTag $nbt): void
    {
        parent::initEntity($nbt);

        $this->pickupMode = $nbt->getByte(self::TAG_PICKUP, self::PICKUP_ANY);
        $this->canHitEntity = $nbt->getByte("canHitEntity", 1) === 1;
    }

    public function saveNBT(): CompoundTag
    {
        $nbt = parent::saveNBT();
        $nbt->setTag("item", $this->item->nbtSerialize());
        $nbt->setByte(self::TAG_PICKUP, $this->pickupMode);
        $nbt->setByte("canHitEntity", $this->canHitEntity ? 1 : 0);
        return $nbt;
    }

    protected function entityBaseTick(int $tickDiff = 1): bool
    {
        if ($this->closed) {
            return false;
        }

        return parent::entityBaseTick($tickDiff);
    }

    public function move(float $dx, float $dy, float $dz): void
    {
        parent::move($dx, $dy, $dz);
        $motion = $this->motion;
        if ($this->isCollided && !$this->canHitEntity) {
            $this->motion = $motion;
        }
    }

    protected function onHitEntity(Entity $entityHit, RayTraceResult $hitResult): void
    {
        if (!$this->canHitEntity) {
            return;
        }
        if ($entityHit->getId() === $this->ownerId) {
            if ($entityHit instanceof Player) {
                $this->pickup($entityHit); //tridents cannot hit their thrower
                return;
            }
        }
        parent::onHitEntity($entityHit, $hitResult);
        $this->canHitEntity = false;
        $this->item->applyDamage(1);
        $newTrident = new self($this->location, $this->item, $this->getOwningEntity(), $this->saveNBT());
        $newTrident->spawnToAll();
        $motion = new Vector3($this->motion->x * -0.01, $this->motion->y * -0.1, $this->motion->z * -0.01);
        $newTrident->setMotion($motion);
        $this->broadcastSound(new TridentHitSound());
    }

    protected function onHitBlock(Block $blockHit, RayTraceResult $hitResult): void
    {
        parent::onHitBlock($blockHit, $hitResult);
        $this->canHitEntity = true;
        $this->broadcastSound(new TridentHitGroundSound());
    }

    public function getItem(): TridentItem
    {
        return clone $this->item;
    }

    public function setItem(TridentItem $item): void
    {
        if ($item->isNull()) {
            throw new \InvalidArgumentException("Trident must have a count of at least 1");
        }
        $this->item = clone $item;
        $this->networkPropertiesDirty = true;
    }

    public function getPickupMode(): int
    {
        return $this->pickupMode;
    }

    public function setPickupMode(int $pickupMode): void
    {
        $this->pickupMode = $pickupMode;
    }

    public function onCollideWithPlayer(Player $player): void
    {
        if ($this->blockHit === null) {
            return;
        }

        $this->pickup($player);
    }

    private function pickup(Player $player): void
    {
        $item = $this->getItem();
        $shouldDespawn = false;
        $playerInventory = match (true) {
            $player->getInventory()->getAddableItemQuantity($item) > 0 => $player->getInventory(),
            default => null
        };

        $ev = new EntityItemPickupEvent($player, $this, $item, $playerInventory);
        if ($player->hasFiniteResources() and $playerInventory === null) {
            $ev->cancel();
        }
        if ($this->pickupMode === self::PICKUP_NONE or ($this->pickupMode === self::PICKUP_CREATIVE and !$player->isCreative())) {
            $ev->cancel();
            $shouldDespawn = true;
        }

        $ev->call();
        if (!$ev->isCancelled()) {
            foreach ($this->getViewers() as $viewer) {
                $viewer->getNetworkSession()->onPlayerPickUpItem($player, $this);
            }
            $ev->getInventory()?->addItem($ev->getItem());
            $shouldDespawn = true;
        }

        if ($shouldDespawn) {
            $this->flagForDespawn();
        }
    }
}
