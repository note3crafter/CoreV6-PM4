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

use pocketmine\inventory\ArmorInventory;
use pocketmine\item\Armor;
use pocketmine\item\ArmorTypeInfo;
use pocketmine\item\Axe;
use pocketmine\item\Hoe;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\item\Pickaxe;
use pocketmine\item\Shovel;
use pocketmine\item\Sword;
use pocketmine\item\ToolTier;

class ItemManager
{
    public static function init()
    {
		//ItemFactory::getInstance()->register(new Trident(new ItemIdentifier(ItemIds::TRIDENT, 0), "Trident"));
		//ItemFactory::getInstance()->register(new NetheriteHelmet(new ItemIdentifier(ItemIds::, 0), "Trident"));
		ItemFactory::getInstance()->register(new Armor(new ItemIdentifier(748, 0), "Netherite Helmet", new ArmorTypeInfo(3, 408, ArmorInventory::SLOT_HEAD)), true);
		ItemFactory::getInstance()->register(new Armor(new ItemIdentifier(749, 0), "Netherite Chestplate", new ArmorTypeInfo(8, 593, ArmorInventory::SLOT_CHEST)), true);
		ItemFactory::getInstance()->register(new Armor(new ItemIdentifier(748, 0), "Netherite Leggings", new ArmorTypeInfo(6, 556, ArmorInventory::SLOT_LEGS)), true);
		ItemFactory::getInstance()->register(new Armor(new ItemIdentifier(751, 0), "Netherite Boots", new ArmorTypeInfo(3, 482, ArmorInventory::SLOT_FEET)), true);
		ItemFactory::getInstance()->register(new Axe(new ItemIdentifier(746, 0), "Netherite Axe", ToolTier::DIAMOND()), true);
		ItemFactory::getInstance()->register(new Hoe(new ItemIdentifier(747, 0), "Netherite Hoe", ToolTier::DIAMOND()), true);
		ItemFactory::getInstance()->register(new Pickaxe(new ItemIdentifier(745, 0), "Netherite Pickaxe", ToolTier::DIAMOND()), true);
		ItemFactory::getInstance()->register(new Shovel(new ItemIdentifier(744, 0), "Netherite Shovel", ToolTier::DIAMOND()), true);
		ItemFactory::getInstance()->register(new Sword(new ItemIdentifier(743, 0), "Netherite Sword", ToolTier::DIAMOND()), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(ItemIds::ELYTRA, 0), "Elytra"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(ItemIds::TRIDENT, 0), "Trident"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(ItemIds::SHIELD, 0), "Shield"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(ItemIds::LEAD, 0), "Lead"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(ItemIds::CROSSBOW, 0), "Crossbow"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(ItemIds::ENDER_EYE, 0), "Ender Eye"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(ItemIds::SADDLE, 0), "Saddle"), true);



		/*ItemFactory::registerItem(new Trident(), true);
		ItemFactory::registerItem(new EyeOfEnder(), true);
		ItemFactory::registerItem(new AcaciaSign(), true);
		ItemFactory::registerItem(new BirchSign(), true);
		ItemFactory::registerItem(new DarkoakSign(), true);
		ItemFactory::registerItem(new JungleSign(), true);
		ItemFactory::registerItem(new SpruceSign(), true);
		ItemFactory::registerItem(new Crossbow(), true);
		ItemFactory::registerItem(new Elytra(), true);
		ItemFactory::registerItem(new Lead(), true);
		ItemFactory::registerItem(new Schild(), true);
		ItemFactory::registerItem(new FireCharge(), true);
		ItemFactory::registerItem(new ShulkerBox(), true);
		ItemFactory::registerItem(new NetheriteHelmet(), true);
		ItemFactory::registerItem(new NetheriteBoots(), true);
		ItemFactory::registerItem(new NetheriteChestplate(), true);
		ItemFactory::registerItem(new NetheriteLeggings(), true);
		ItemFactory::registerItem(new NetheriteIngot(), true);
		ItemFactory::registerItem(new NetheriteScrap(), true);
		ItemFactory::registerItem(new NetheriteSword(743, 0, "Netherite Sword", TTool::TIER_NETHERITE), true);
		ItemFactory::registerItem(new NetheriteShovel(744, 0, "Netherite Shovel", TTool::TIER_NETHERITE), true);
		ItemFactory::registerItem(new NetheritePickaxe(745, 0, "Netherite Hoe", TTool::TIER_NETHERITE), true);
		ItemFactory::registerItem(new NetheriteAxe(746, 0, "Netherite Axe", TTool::TIER_NETHERITE), true);
		ItemFactory::registerItem(new NetheriteHoe(747, 0, "Netherite Hoe", TTool::TIER_NETHERITE), true);
		ItemFactory::registerItem(new Beacon(), true);
		ItemFactory::registerItem(new JukeboxItem(), true);
		ItemFactory::registerItem(new Saddle(), true);
		ItemFactory::registerItem(new EndCrystal(), true);
		ItemFactory::registerItem(new EnchantedBook(), true);
		//ItemFactory::registerItem(new Map(), true);
		//ItemFactory::registerItem(new EmptyMap(), true);
		ItemFactory::registerItem(new Boat(), true);

		//ItemFactory::addCreativItem(new Item(525, 0, "Netherite Block"), true);

		ItemFactory::registerItem(new Firework(), true);
		ItemFactory::registerItem(new Record(500, "13", LevelSoundEventPacket::SOUND_RECORD_13), true);
		ItemFactory::registerItem(new Record(501, "Cat", LevelSoundEventPacket::SOUND_RECORD_CAT), true);
		ItemFactory::registerItem(new Record(502, "Blocks", LevelSoundEventPacket::SOUND_RECORD_BLOCKS), true);
		ItemFactory::registerItem(new Record(503, "Chirp", LevelSoundEventPacket::SOUND_RECORD_CHIRP), true);
		ItemFactory::registerItem(new Record(504, "Far", LevelSoundEventPacket::SOUND_RECORD_FAR), true);
		ItemFactory::registerItem(new Record(505, "Mall", LevelSoundEventPacket::SOUND_RECORD_MALL), true);
		ItemFactory::registerItem(new Record(506, "Mellohi", LevelSoundEventPacket::SOUND_RECORD_MELLOHI), true);
		ItemFactory::registerItem(new Record(507, "Stal", LevelSoundEventPacket::SOUND_RECORD_STAL), true);
		ItemFactory::registerItem(new Record(508, "Strad", LevelSoundEventPacket::SOUND_RECORD_STRAD), true);
		ItemFactory::registerItem(new Record(509, "Ward", LevelSoundEventPacket::SOUND_RECORD_WARD), true);
		ItemFactory::registerItem(new Record(510, "11", LevelSoundEventPacket::SOUND_RECORD_11), true);
		ItemFactory::registerItem(new Record(511, "Wait", LevelSoundEventPacket::SOUND_RECORD_WAIT), true);
		//ItemFactory::registerItem(new Record(759, "Pigstep Disc"));*/

        //Item::initCreativeItems();

    }
}
