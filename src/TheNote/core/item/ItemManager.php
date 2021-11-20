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

use pocketmine\block\Block;
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
use TheNote\core\blocks\BlockManager;

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
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(720, 0), "Campfire"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(449, 0), "Turtle Helmet"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(451, 0), "Bleach"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(453, 0), "Ice Bomb"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(469, 0), "Turtle Helmet"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(499, 0), "Compound"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(736, 0), "Honeycomb"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(737, 0), "Honey Bottle"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(741, 0), "Lodestonecompass"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(742, 0), "Netherite Ingot"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(752, 0), "Netherite Scrap"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(753, 0), "Crimson Sign"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(754, 0), "Warped Sign"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(755, 0), "Crimson Door"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(756, 0), "Warped Door"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(757, 0), "Warped Fungus on a Stick"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(758, 0), "Chain"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(759, 0), "Record Pigstep"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(760, 0), "Nether Sprouts"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(761, 0), "Soul Campfire"), true);

		/*ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-215, 0), "Light Block"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-216, 0), "Wither Rose"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-218, 0), "Bee Nest"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-219, 0), "Beehive"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-220, 0), "Honey Block"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-221, 0), "Honeycomb Block"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-222, 0), "Lodestone"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-223, 0), "Crimson Roots"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-224, 0), "Warped Roots"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-225, 0), "Crimson Stem"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-226, 0), "Warped Stem"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-227, 0), "Warped Wart Block"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-228, 0), "Crimson Fungus"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-229, 0), "Warped Fungus"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-230, 0), "Shroomlight"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-231, 0), "Weeping Vines"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-232, 0), "Crimson Nylium"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-233, 0), "Warped Nylium"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-234, 0), "Basalt"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-235, 0), "Polished Basalt"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-236, 0), "Soul Soil"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-237, 0), "Soul Fire"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-238, 0), "item.Nether Sprouts"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-239, 0), "Target"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-240, 0), "Stripped Crimson Stem"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-241, 0), "Stripped Warped Stem"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-242, 0), "Crimson Planks"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-243, 0), "Warped Planks"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-244, 0), "item.Crimson Door"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-245, 0), "item.Warped Door"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-246, 0), "Crimson Trapdoor"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-247, 0), "Warped Trapdoor"), true);


		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-250, 0), "Crimson Standing Sign"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-251, 0), "Warped Standing Sign"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-252, 0), "Crimson Wall Sign"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-253, 0), "Warped Wall Sign"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-254, 0), "Crimson Stairs"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-255, 0), "Warped Stairs"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-256, 0), "Crimson Fence"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-257, 0), "Warped Fence"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-258, 0), "Crimson Fence Gate"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-259, 0), "Warped Fence Gate"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-260, 0), "Crimson Button"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-261, 0), "Warped Button"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-262, 0), "Crimson Pressure Plate"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-263, 0), "Warped Pressure Plate"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-264, 0), "Crimson Slab"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-265, 0), "Warped Slab"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-266, 0), "Crimson Double Slab"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-267, 0), "Warped Double Slab"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-268, 0), "Soul Torch"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-269, 0), "Soul Lantern"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-270, 0), "Netherite Block"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-271, 0), "Ancient Debris"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-272, 0), "Respawn Anchor"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-273, 0), "Blackstone"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-274, 0), "Polished Blackstone Bricks"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-275, 0), "Polished Blackstone Brick Stairs"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-276, 0), "Blackstone Stairs"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-277, 0), "Blackstone Wall"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-278, 0), "Polished Blackstone Brick Wall"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-279, 0), "Chiseled Polished Blackstone"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-280, 0), "Cracked Polished Blackstone Bricks"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-281, 0), "Gilded Blackstone"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-282, 0), "Blackstone Slab"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-283, 0), "Blackstone Double Slab"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-284, 0), "Polished Blackstone Brick Slab"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-285, 0), "Polished Blackstone Brick Double Slab"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-286, 0), "item.Chain"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-287, 0), "Twisting Vines"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-288, 0), "Nether Gold Ore"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-289, 0), "Crying Obsidian"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-290, 0), "item.Soul Campfire"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-291, 0), "Polished Blackstone"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-292, 0), "Polished Blackstone Stairs"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-293, 0), "Polished Blackstone Slab"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-294, 0), "Polished Blackstone Double Slab"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-295, 0), "Polished Blackstone Pressure Plate"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-296, 0), "Polished Blackstone Button"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-297, 0), "Polished Blackstone Wall"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-298, 0), "Warped Hyphae"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-299, 0), "Crimson Hyphae"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-300, 0), "Stripped Crimson Hyphae"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-301, 0), "Stripped Warped Hyphae"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-302, 0), "Chiseled Nether Bricks"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-303, 0), "Cracked Nether Bricks"), true);
		ItemFactory::getInstance()->register(new Item(new ItemIdentifier(-304, 0), "Quartz Bricks"), true);*/



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
