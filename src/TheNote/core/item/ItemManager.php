<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\item;

use pocketmine\block\BlockFactory;
use pocketmine\block\BlockLegacyIds as Ids;
use pocketmine\block\utils\RecordType;
use pocketmine\inventory\ArmorInventory;
use pocketmine\item\Armor;
use pocketmine\item\ArmorTypeInfo;
use pocketmine\item\Axe;
use pocketmine\item\Hoe;
use pocketmine\item\Item;
use pocketmine\item\ItemBlock;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\item\Pickaxe;
use pocketmine\item\Record;
use pocketmine\item\Shovel;
use pocketmine\item\Sword;
use pocketmine\item\ToolTier;
use pocketmine\lang\Translatable;
use TheNote\core\utils\CustomIds;

class ItemManager
{
    public static function init()
    {
        $class = new \ReflectionClass(ToolTier::class);
        $register = $class->getMethod('register');
        $register->setAccessible(true);
        $constructor = $class->getConstructor();
        $constructor->setAccessible(true);
        $instance = $class->newInstanceWithoutConstructor();
        $constructor->invoke($instance, 'netherite', 6, 2031, 9, 10);
        $register->invoke(null, $instance);

        $class = new \ReflectionClass(RecordType::class);
        $register = $class->getMethod('register');
        $register->setAccessible(true);
        $constructor = $class->getConstructor();
        $constructor->setAccessible(true);
        $instance = $class->newInstanceWithoutConstructor();
        $constructor->invoke($instance, 'disk_pigstep', 'Lena Raine - Pigstep', CustomIds::RECORD_PIGSTEP_SOUND_ID, new Translatable('item.record_pigstep.desc', []));
        //$constructor->invoke($instance, 'disk_otherside ', 'Lena Raine - Otherside ', CustomIds::RECORD_OTHERSIDE_SOUND_ID, new Translatable('item.record_otherside.desc', []));

        $register->invoke(null, $instance);

        $factory = ItemFactory::getInstance();
        //$factory->register(new Trident(new ItemIdentifier(ItemIds::TRIDENT, 0), "Trident"));
        $factory->register(new FlintAndSteel(new ItemIdentifier(ItemIds::FLINT_AND_STEEL, 0), "Flint and Steel"), true);
        $factory->register(new Record(new ItemIdentifier(CustomIds::RECORD_PIGSTEP, 0), RecordType::DISK_PIGSTEP(), "Pigstep"), true);
        $factory->register(new ItemBlock(new ItemIdentifier(CustomIds::CRIMSON_DOOOR_ITEM, 0), BlockFactory::getInstance()->get(CustomIds::CRIMSON_DOOR_BLOCK, 0)), true);
        $factory->register(new ItemBlock(new ItemIdentifier(CustomIds::WARPED_DOOR_ITEM, 0), BlockFactory::getInstance()->get(CustomIds::WARPED_DOOR_BLOCK, 0)), true);
        $factory->register(new ItemBlock(new ItemIdentifier(CustomIds::CAMPFIRE_ITEM, 0), BlockFactory::getInstance()->get(Ids::CAMPFIRE, 0)), true);
        $factory->register(new ItemBlock(new ItemIdentifier(CustomIds::SOUL_CAMPFIRE_ITEM, 0), BlockFactory::getInstance()->get(CustomIds::SOUL_CAMPFIRE_BLOCK, 0)), true);
        $factory->register(new Item(new ItemIdentifier(CustomIds::ITEM_NETHERITE_INGOT, 0), 'Netherite Ingot'), true);
        $factory->register(new Item(new ItemIdentifier(CustomIds::ITEM_NETHERITE_SCRAP, 0), 'Netherite Scrap'), true);
        $factory->register(new Sword(new ItemIdentifier(CustomIds::ITEM_NETHERITE_SWORD, 0), 'Netherite Sword', ToolTier::NETHERITE()), true);
        $factory->register(new Shovel(new ItemIdentifier(CustomIds::ITEM_NETHERITE_SHOVEL, 0), 'Netherite Shovel', ToolTier::NETHERITE()), true);
        $factory->register(new Pickaxe(new ItemIdentifier(CustomIds::ITEM_NETHERITE_PICKAXE, 0), 'Netherite Pickaxe', ToolTier::NETHERITE()), true);
        $factory->register(new Axe(new ItemIdentifier(CustomIds::ITEM_NETHERITE_AXE, 0), 'Netherite Axe', ToolTier::NETHERITE()), true);
        $factory->register(new Hoe(new ItemIdentifier(CustomIds::ITEM_NETHERITE_HOE, 0), 'Netherite Hoe', ToolTier::NETHERITE()), true);
        $factory->register(new Armor(new ItemIdentifier(CustomIds::NETHERITE_HELMET, 0), 'Netherite Helmet', new ArmorTypeInfo(6, 407, ArmorInventory::SLOT_HEAD)), true);
        $factory->register(new Armor(new ItemIdentifier(CustomIds::NETHERITE_CHESTPLATE, 0), 'Netherite Chestplate', new ArmorTypeInfo(3, 592, ArmorInventory::SLOT_CHEST)), true);
        $factory->register(new Armor(new ItemIdentifier(CustomIds::NETHERITE_LEGGINGS, 0), 'Netherite Leggings', new ArmorTypeInfo(3, 481, ArmorInventory::SLOT_LEGS)), true);
        $factory->register(new Armor(new ItemIdentifier(CustomIds::NETHERITE_BOOTS, 0), 'Netherite Boots', new ArmorTypeInfo(6, 555, ArmorInventory::SLOT_FEET)), true);
        $factory->register(new ItemBlock(new ItemIdentifier(CustomIds::CHAIN_ITEM, 0), BlockFactory::getInstance()->get(CustomIds::CHAIN_BLOCK, 0)), true);
        $factory->register(new LodestoneCompass(new ItemIdentifier(CustomIds::LODESTONE_COMPASS,0)), true);

        ItemFactory::getInstance()->register(new Armor(new ItemIdentifier(449, 0), "Turtle Helmet", new ArmorTypeInfo(2,  275, ArmorInventory::SLOT_HEAD)), true);
        //ItemFactory::getInstance()->register(new Shield(new ItemIdentifier(ItemIds::SHIELD, 0), "Shield"), true);
        ItemFactory::getInstance()->register(new Item(new ItemIdentifier(ItemIds::ELYTRA, 0), "Elytra"), true);
        ItemFactory::getInstance()->register(new Item(new ItemIdentifier(ItemIds::TRIDENT, 0), "Trident"), true);
        ItemFactory::getInstance()->register(new Item(new ItemIdentifier(ItemIds::LEAD, 0), "Lead"), true);
        ItemFactory::getInstance()->register(new Item(new ItemIdentifier(ItemIds::CROSSBOW, 0), "Crossbow"), true);
        ItemFactory::getInstance()->register(new Item(new ItemIdentifier(ItemIds::ENDER_EYE, 0), "Ender Eye"), true);
        ItemFactory::getInstance()->register(new Item(new ItemIdentifier(ItemIds::SADDLE, 0), "Saddle"), true);
        ItemFactory::getInstance()->register(new Item(new ItemIdentifier(ItemIds::FIREWORKS, 0), "Fireworks"), true);
        //ItemFactory::getInstance()->register(new NetheriteHelmet(new ItemIdentifier(ItemIds::, 0), "Trident"));

        /*ItemFactory::registerItem(new Trident(), true);
        ItemFactory::registerItem(new EndCrystal(), true);
        ItemFactory::registerItem(new EnchantedBook(), true);
        //ItemFactory::registerItem(new Map(), true);
        //ItemFactory::registerItem(new EmptyMap(), true);
        ItemFactory::registerItem(new Boat(), true);
        //ItemFactory::addCreativItem(new Item(525, 0, "Netherite Block"), true);
        ItemFactory::registerItem(new Firework(), true);*/
        //Item::initCreativeItems();

    }
}
