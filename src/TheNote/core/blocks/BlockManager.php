<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\blocks;

use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockFactory as VBF;
use pocketmine\block\BlockIdentifier as BID;
use pocketmine\block\BlockIdentifierFlattened as BIF;
use pocketmine\block\BlockLegacyIds as Ids;
use pocketmine\block\BlockToolType;
use pocketmine\block\Door;
use pocketmine\block\Fence;
use pocketmine\block\FenceGate;
use pocketmine\block\Opaque;
use pocketmine\block\Slab;
use pocketmine\block\Stair;
use pocketmine\block\StoneButton;
use pocketmine\block\tile\Sign;
use pocketmine\block\utils\TreeType;
use pocketmine\block\VanillaBlocks;
use pocketmine\block\WoodenButton;
use pocketmine\block\WoodenTrapdoor;
use pocketmine\item\ItemBlock;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\item\ToolTier;
use pocketmine\item\VanillaItems;
use TheNote\core\utils\CustomIds;
use TheNote\core\tile\Campfire as TileCampfire;
use TheNote\core\tile\Lodestone as TileLodestone;

class BlockManager
{

    public static function init(): void
    {
        $class = new \ReflectionClass(TreeType::class);
        $register = $class->getMethod('register');
        $register->setAccessible(true);
        $constructor = $class->getConstructor();
        $constructor->setAccessible(true);
        $instance = $class->newInstanceWithoutConstructor();
        $constructor->invoke($instance, 'crimson', 'Crimson', 6);
        $register->invoke(null, $instance);

        $instance = $class->newInstanceWithoutConstructor();
        $constructor->invoke($instance, 'warped', 'Warped', 7);
        $register->invoke(null, $instance);

        $bf = BlockFactory::getInstance();
        //Defaults
        $bf->register(new FlowerPot(new BID(IDS::FLOWER_POT_BLOCK, 0, ItemIds::FLOWER_POT), "Flower Pot",new BlockBreakInfo(0, BlockToolType::NONE, 0, 0)), true);

        //Bedrock 1.11
        $bf->register(new Campfire(new BID(Ids::CAMPFIRE, 0, CustomIds::CAMPFIRE_ITEM, TileCampfire::class), "Campfire", new BlockBreakInfo(2, BlockToolType::AXE, 0, 10)));
        $bf->register(new Composter(new BID(IDS::COMPOSTER, 0, ItemIds::COMPOSTER)));
        $bf->register(new Lectern(new BID(IDS::LECTERN, 0, ItemIds::LECTERN), "Lectern", new BlockBreakInfo(2.5, BlockToolType::AXE,0, 2.5)),true);
        $bf->register(new Stonecutter(new BID(IDS::STONECUTTER_BLOCK, 0, ItemIds::STONECUTTER), "Stonecutter", new BlockBreakInfo(3.5, BlockToolType::PICKAXE,ToolTier::WOOD()->getHarvestLevel(), 3.5)),true);
        $bf->register(new Dropper(new BID(IDS::DROPPER, 0, ItemIds::DROPPER), "Dropper", new BlockBreakInfo(3.5, BlockToolType::PICKAXE,ToolTier::WOOD()->getHarvestLevel(), 3.5)),true);
        $bf->register(new Dispenser(new BID(IDS::DISPENSER, 0, ItemIds::DISPENSER), "Dispenser", new BlockBreakInfo(3.5, BlockToolType::PICKAXE,ToolTier::WOOD()->getHarvestLevel(), 3.5)),true);


        //Bedrock 1.13
        $bf->register(new Roots(new BID(471, 0, -216), "Whiter Rose", BlockBreakInfo::instant()), true);

        //Bedrock 1.14
        $bf->register(new BeeNest(new BID(473, 0, -218), "Bee Nest", new BlockBreakInfo(0.3, BlockToolType::AXE, 0.3)), true);
        $bf->register(new Beehive(new BID(474, 0, -219,),"Bee Hive", new BlockBreakInfo(0.6, BlockToolType::AXE, 0.6)), true);
        $bf->register(new HoneyBlock(new BID(475, 0, -220),"Honey Block", new BlockBreakInfo(0)), true);
        $bf->register(new HoneyCombBlock(new BID(476, 0, -221), "Honeycomb Block", new BlockBreakInfo(0.6)), true);

        //Nether Update 1.16
        $bf->register(new Opaque(new BID(CustomIds::ANCIENT_DEBRIS_BLOCK, 0, CustomIds::ANCIENT_DEBRIS_ITEM), "Ancient Debris", new BlockBreakInfo(30, BlockToolType::PICKAXE, ToolTier::DIAMOND()->getHarvestLevel(), 6000)), true);
        $bf->register(new Basalt(new BID(CustomIds::BASALT_BLOCK, 0, CustomIds::BASALT_ITEM), "Basalt", new BlockBreakInfo(1.25, BlockToolType::PICKAXE, ToolTier::WOOD()->getHarvestLevel(), 4.2)), true);
        $bf->register(new PolishedBasalt(new BID(CustomIds::POLISHED_BASALT_BLOCK, 0, CustomIds::POLISHED_BASALT_ITEM), "Polished Basalt", new BlockBreakInfo(1.25, BlockToolType::PICKAXE, ToolTier::WOOD()->getHarvestLevel(), 4.2)), true);
        $bf->register(new Fungus(new BID(CustomIds::FUNGUS_BLOCK, 0, CustomIds::FUNGUS_ITEM), "Crimson Fungus", BlockBreakInfo::instant()), true);
        $bf->register(new WarpedFungus(new BID(CustomIds::WARPED_FUNGUS_BLOCK, 0, CustomIds::WARPED_FUNGUS_ITEM), "Warped Fungus", BlockBreakInfo::instant()), true);
        $bf->register(new SoulSoil(new BID(CustomIds::SOUL_SOIL_BLOCK, 0, CustomIds::SOUL_SOIL_ITEM), "Soul Soil", new BlockBreakInfo(0.5, BlockToolType::SHOVEL)), true);
        $bf->register(new SoulFire(new BID(CustomIds::SOUL_FIRE_BLOCK, 0, CustomIds::SOUL_FIRE_ITEM), "Soul Fire", BlockBreakInfo::instant()), true);
        $bf->register(new Nylium(new BID(CustomIds::NYLIUM_BLOCK, 0, CustomIds::NYLIUM_ITEM), "Crimson Nylium", new BlockBreakInfo(1, BlockToolType::PICKAXE)), true);
        $bf->register(new WarpedNylium(new BID(CustomIds::WARPED_NYLIUM_BLOCK, 0, CustomIds::WARPED_NYLIUM_ITEM), "Warped Nylium", new BlockBreakInfo(1, BlockToolType::PICKAXE, ToolTier::WOOD()->getHarvestLevel())), true);
        $bf->register(new Opaque(new BID(CustomIds::NETHERITE_BLOCK, 0, CustomIds::NETHERITE_BLOCK_ITEM), "Netherite Block", new BlockBreakInfo(50, BlockToolType::PICKAXE, ToolTier::DIAMOND()->getHarvestLevel(), 6000)), true);
        $bf->register(new NetherSprouts(new BID(CustomIds::NETHER_SPROUTS_BLOCK, 0, CustomIds::NETHER_SPROUTS_ITEM), "Nether Sprouts", BlockBreakInfo::instant(BlockToolType::SHEARS)));
        $bf->register(new Shroomlight(new BID(CustomIds::SHROOMLIGHT_BLOCK, 0, CustomIds::SHROOMLIGHT_ITEM), "Shroomlight", new BlockBreakInfo(1, BlockToolType::HOE)), true);
        $bf->register(new SoulLantern(new BID(CustomIds::SOUL_LANTERN_BLOCK, 0, CustomIds::SOUL_LANTERN_ITEM), "Soul Lantern", new BlockBreakInfo(3.5, BlockToolType::PICKAXE, ToolTier::WOOD()->getHarvestLevel(), 3.5)), true);
        $bf->register(new SoulTorch(new BID(CustomIds::SOUL_TORCH_BLOCK, 0, CustomIds::SOUL_TORCH_ITEM), "Soul Torch", BlockBreakInfo::instant()), true);
        $bf->register(new NetherWartBlock(new BID(CustomIds::NETHER_WART_BLOCK, 0), "Nether Wart Block", new BlockBreakInfo(1, BlockToolType::HOE, 0, 5)), true);
        $bf->register(new WarpedWartBlock(new BID(CustomIds::WARPED_WART_BLOCK, 0, CustomIds::WARPED_WART_ITEM), "Warped Wart Block", new BlockBreakInfo(1, BlockToolType::HOE, 0, 5)), true);
        $bf->register(new CryingObsidian(new BID(CustomIds::CRYING_OBSIDIAN_BLOCK, 0, CustomIds::CRYING_OBSIDIAN_ITEM), "Crying Obsidian", new BlockBreakInfo(50, BlockToolType::PICKAXE, ToolTier::DIAMOND()->getHarvestLevel(), 6000)), true);
        $bf->register(new Target(new BID(CustomIds::TARGET_BLOCK, 0, CustomIds::TARGET_ITEM), "Target", BlockBreakInfo::instant(BlockToolType::HOE)), true);
        $bf->register(new NetherGoldOre(new BID(CustomIds::NETHER_GOLD_ORE_BLOCK, 0, CustomIds::NETHER_GOLD_ORE_ITEM), "Nether Gold Ore", new BlockBreakInfo(3, BlockToolType::PICKAXE, ToolTier::WOOD()->getHarvestLevel(), 3)), true);
        $bf->register(new RespawnAnchor(new BID(CustomIds::RESPAWN_ANCHOR_BLOCK, 0, CustomIds::RESPAWN_ANCHOR_ITEM), "Respawn Anchor", new BlockBreakInfo(50, BlockToolType::PICKAXE, ToolTier::DIAMOND()->getHarvestLevel(), 6000)), true);
        $bf->register(new Blackstone(new BID(CustomIds::BLACKSTONE_BLOCK, 0, CustomIds::BLACKSTONE_ITEM), "Blackstone", new BlockBreakInfo(1.5, BlockToolType::PICKAXE, ToolTier::WOOD()->getHarvestLevel(), 6)), true);
        $bf->register(new PolishedBlackStone(new BID(CustomIds::POLISHED_BLACKSTONE_BLOCK, 0, CustomIds::POLISHED_BLACKSTONE_ITEM), "Polished Blackstone", new BlockBreakInfo(1.5, BlockToolType::PICKAXE, ToolTier::WOOD()->getHarvestLevel(), 6)), true);
        $bf->register(new ChiseledPolishedBlackstone(new BID(CustomIds::CHISELED_POLISHED_BLACKSTONE_BLOCK, 0, CustomIds::CHISELED_POLISHED_BLACKSTONE_ITEM), "Chiseled Polished Blackstone", new BlockBreakInfo(1.5, BlockToolType::PICKAXE, ToolTier::WOOD()->getHarvestLevel(), 6)), true);
        $bf->register(new GildedBlackstone(new BID(CustomIds::GILDED_BLACKSTONE_BLOCK, 0, CustomIds::GILDED_BLACKSTONE_ITEM), "Gilded Blackstone", new BlockBreakInfo(1.5, BlockToolType::PICKAXE, ToolTier::WOOD()->getHarvestLevel(), 6)), true);
        $bf->register(new Chain(new BID(CustomIds::CHAIN_BLOCK, 0, CustomIds::CHAIN_ITEM), "Chain", new BlockBreakInfo(5, BlockToolType::PICKAXE, ToolTier::WOOD()->getHarvestLevel(), 6)), true);
        $bf->register(new TwistingVines(new BID(CustomIds::TWISTING_VINES_BLOCK, 0, CustomIds::TWISTING_VINES_ITEM), "Twisting Vines", BlockBreakInfo::instant()), true);
        $bf->register(new WeepingVines(new BID(CustomIds::WEEPING_VINES_BLOCK, 0, CustomIds::WEEPING_VINES_ITEM), "Weeping Vines", BlockBreakInfo::instant()), true);
        $bf->register(new Roots(new BID(CustomIds::CRIMSON_ROOTS_BLOCK, 0, CustomIds::CRIMSON_ROOTS_ITEM), "Crimson Roots", BlockBreakInfo::instant()), true);
        $bf->register(new Roots(new BID(CustomIds::WARPED_ROOTS_BLOCK, 0, CustomIds::WARPED_ROOTS_ITEM), "Warped Roots", BlockBreakInfo::instant()), true);
        $bf->register(new Planks(new BID(CustomIds::CRIMSON_PLANKS_BLOCK, 0, CustomIds::CRIMSON_PLANKS_ITEM), "Crimson Planks", new BlockBreakInfo(2, BlockToolType::AXE, 0, 3)), true);
        $bf->register(new Planks(new BID(CustomIds::WARPED_PLANKS_BLOCK, 0, CustomIds::WARPED_PLANKS_ITEM), "Warped Planks", new BlockBreakInfo(2, BlockToolType::AXE, 0, 3)), true);
        $bf->register(new Wood(new BID(CustomIds::CRIMSON_STEM_BLOCK, 0, CustomIds::CRIMSON_STEM_ITEM), "Crimson Stem", new BlockBreakInfo(2, BlockToolType::AXE, 0, 10), TreeType::CRIMSON(), false), true);
        $bf->register(new Wood(new BID(CustomIds::WARPED_STEM_BLOCK, 0, CustomIds::WARPED_STEM_ITEM), "Warped Stem", new BlockBreakInfo(2, BlockToolType::AXE, 0, 10), TreeType::WARPED(), false), true);
        $bf->register(new Log(new BID(CustomIds::CRIMSON_STRIPPED_STEM_BLOCK, 0, CustomIds::CRIMSON_STRIPPED_STEM_ITEM), "Crimson Stripped Stem", new BlockBreakInfo(2, BlockToolType::AXE, 0, 10), TreeType::CRIMSON(), true), true);
        $bf->register(new Log(new BID(CustomIds::WARPED_STRIPPED_STEM_BLOCK, 0, CustomIds::WARPED_STRIPPED_STEM_ITEM), "Warped Stripped Stem", new BlockBreakInfo(2, BlockToolType::AXE, 0, 10), TreeType::WARPED(), true));
        $bf->register(new Hyphae(new BID(CustomIds::CRIMSON_HYPHAE_BLOCK, 0, CustomIds::CRIMSON_HYPHAE_ITEM), "Crimson Hyphae", new BlockBreakInfo(2, BlockToolType::AXE, 0, 10), TreeType::CRIMSON(), false), true);
        $bf->register(new Hyphae(new BID(CustomIds::WARPED_HYPHAE_BLOCK, 0, CustomIds::WARPED_HYPHAE_ITEM), "Warped Hyphae", new BlockBreakInfo(2, BlockToolType::AXE, 0, 10), TreeType::WARPED(), false));
        $bf->register(new Hyphae(new BID(CustomIds::CRIMSON_STRIPPED_HYPHAE_BLOCK, 0, CustomIds::CRIMSON_STRIPPED_HYPHAE_ITEM), "Crimson Stripped Hyphae", new BlockBreakInfo(2, BlockToolType::AXE, 0, 10), TreeType::CRIMSON(), true), true);
        $bf->register(new Hyphae(new BID(CustomIds::WARPED_STRIPPED_HYPHAE_BLOCK, 0, CustomIds::WARPED_STRIPPED_HYPHAE_ITEM), "Warped Stripped Hyphae", new BlockBreakInfo(2, BlockToolType::AXE, 0, 10), TreeType::WARPED(), true), true);
        $bf->register(new Door(new BID(CustomIds::CRIMSON_DOOR_BLOCK, 0, CustomIds::CRIMSON_DOOOR_ITEM), "Crimson Door", new BlockBreakInfo(3, BlockToolType::AXE)), true);
        $bf->register(new Door(new BID(CustomIds::WARPED_DOOR_BLOCK, 0, CustomIds::WARPED_DOOR_ITEM), "Warped Door", new BlockBreakInfo(3, BlockToolType::AXE)), true);
        $bf->register(new Fence(new BID(CustomIds::CRIMSON_FENCE_BLOCK, 0, CustomIds::CRIMSON_FENCE_ITEM), "Crimson Fence", new BlockBreakInfo(2, BlockToolType::AXE, 0, 3)), true);
        $bf->register(new Fence(new BID(CustomIds::WARPED_FENCE_BLOCK, 0, CustomIds::WARPED_FENCE_ITEM), "Warped Fence", new BlockBreakInfo(2, BlockToolType::AXE, 0, 3)), true);
        $bf->register(new FenceGate(new BID(CustomIds::CRIMSON_FENCE_GATE_BLOCK, 0, CustomIds::CRIMSON_FENCE_GATE_ITEM), "Crimson Fence Gate", new BlockBreakInfo(2, BlockToolType::AXE, 0, 3)), true);
        $bf->register(new FenceGate(new BID(CustomIds::WARPED_FENCE_GATE_BLOCK, 0, CustomIds::WARPED_FENCE_GATE_ITEM), "Warped Fence Gate", new BlockBreakInfo(2, BlockToolType::AXE, 0, 3)), true);
        $bf->register(new WoodenTrapdoor(new BID(CustomIds::CRIMSON_TRAPDOOR_BLOCK, 0, CustomIds::CRIMSON_TRAPDOOR_ITEM), "Crimson Trapdoor", new BlockBreakInfo(3, BlockToolType::AXE, 0, 15)), true);
        $bf->register(new WoodenTrapdoor(new BID(CustomIds::WARPED_TRAPDOOR_BLOCK, 0, CustomIds::WARPED_TRAPDOOR_ITEM), "Warped Trapdoor", new BlockBreakInfo(3, BlockToolType::AXE, 0, 15)), true);
        $bf->register(new Stair(new BID(CustomIds::BLACKSTONE_STAIRS_BLOCK, 0, CustomIds::BLACKSTONE_STAIRS_ITEM), "Blackstone Stairs", new BlockBreakInfo(3, BlockToolType::AXE, 0, 6)), true);
        $bf->register(new Stair(new BID(CustomIds::CRIMSON_STAIRS_BLOCK, 0, CustomIds::CRIMSON_STAIRS_ITEM), "Crimson Stairs", new BlockBreakInfo(3, BlockToolType::AXE, 0, 6)), true);
        $bf->register(new Stair(new BID(CustomIds::POLISHED_BLACKSTONE_STAIRS_BLOCK, 0, CustomIds::POLISHED_BLACKSTONE_STAIRS_ITEM), "Polished Blackstone Stairs", new BlockBreakInfo(3, BlockToolType::AXE, 0, 6)), true);
        $bf->register(new Stair(new BID(CustomIds::WARPED_STAIRS_BLOCK, 0, CustomIds::WARPED_STAIRS_ITEM), "Warped Stairs", new BlockBreakInfo(3, BlockToolType::AXE, 0, 6)), true);
        $bf->register(new Stair(new BID(CustomIds::POLISHED_BLACKSTONE_BRICK_STAIRS_BLOCK, 0, CustomIds::POLISHED_BLACKSTONE_BRICK_STAIRS_ITEM), "Polished Blackstone Brick Stairs", new BlockBreakInfo(3, BlockToolType::AXE, 0, 6)), true);
        $bf->register(new Stair(new BID(CustomIds::POLISHED_BLACKSTONE_STAIRS_BLOCK, 0, CustomIds::POLISHED_BLACKSTONE_STAIRS_ITEM), "Polished Blackstone Stairs", new BlockBreakInfo(3, BlockToolType::AXE, 0, 6)), true);
        $bf->register(new Stair(new BID(CustomIds::WARPED_STAIRS_BLOCK, 0, CustomIds::WARPED_STAIRS_ITEM), "Warped Stairs", new BlockBreakInfo(3, BlockToolType::AXE, 0, 6)), true);
        $bf->register(new SoulCampfire(new BID(CustomIds::SOUL_CAMPFIRE_BLOCK, 0, CustomIds::SOUL_CAMPFIRE_ITEM, TileCampfire::class), "Soul Campfire", new BlockBreakInfo(2, BlockToolType::AXE, 0, 10)));
        $bf->register(new Lodestone(new BID(CustomIds::LODESTONE_BLOCK, 0, CustomIds::LODESTONE_ITEM, TileLodestone::class), "Lodestone", new BlockBreakInfo(2, BlockToolType::PICKAXE, 0, 10)));

        $bf->register(new CrimsonSign(new BIF(505, [507], 0, -250, Sign::class), "", new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
        //$bf->register(new WarpedSign(new BIF(506, [508 ],0, -251), Sign::class));

        $bf->register(new Allow(new BID(211, 0, 210), "Allow", new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
        $bf->register(new Deny(new BID(211, 0, 211), "Deny", new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
        $bf->register(new ChiseledNetherBricks(new BID(557, 0, -302), "Chiseled Nether Bricks", new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
        $bf->register(new ChiseledPolishedBlackstone(new BID(534, 0, -279),"Chiseled Polished Blackstone",  new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
        $bf->register(new CrackedPolishedBlackstoneBricks(new BID(535, 0, -280), "Cracked Polished Blackstone Bricks" ,new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
        $bf->register(new CrackedNetherBricks(new BID(558,0,-303), "Cracked Nether Bricks", new BlockBreakInfo(0.9, BlockToolType::PICKAXE)), true);
        $bf->register(new QuartzBricks(new BID(559, 0, -304), "Quarz Bricks",new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);

        $bf->register(new PolishedBlackstoneSlab(new BIF(548, [549] ,0, -293), "Polished Blackstone Slab" , new BlockBreakInfo(1.5, BlockToolType::PICKAXE, ToolTier::WOOD()->getHarvestLevel(), 6)), true);
        $bf->register(new PolishedBlackstoneBrickSlab(new BIF(539, [540],0, -284), "Polished Blackstone Brick Slab", new BlockBreakInfo(1.5, BlockToolType::PICKAXE, ToolTier::WOOD()->getHarvestLevel(), 6)), true);
        $bf->register(new CrimsonSlab(new BIF(519, [521],0, -264), "Crimson Slab", new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
        $bf->register(new WarpedSlab(new BIF(520, [522], 0,-265), "Warped Slab" , new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
        $bf->register(new BlackstoneSlab(new BIF(536, [538], 0, -281), "Blackstone Slab", new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
        $bf->register(new PolishedBlackstoneBrickDoubleSlab(new BID(540, 0, -285), "Polished Blackstone Brick Double Slab", new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
        $bf->register(new PolishedBlackstoneDoubleSlab(new BID(549, 0, -294),"Polished Blackstone Double Slab", new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
        $bf->register(new CrimsonDoubleSlab(new BID(521, 0, -266), "Crimson Double Slab", new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
        $bf->register(new WarpedDoubleSlab(new BID(522, 0, -267), "Warped Double Slap", new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
        $bf->register(new BlackstoneDoubleSlab(new BID(538, 0, -283), "Blackstone Double Slab", new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);

        $bf->register(new PolishedBlackstoneWall(new BID(552, 0, -297), "Polished Blackstone Wall", new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
        $bf->register(new BlackstoneWall(new BID(532, 0, -277),"Blackstone Wall", new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
        $bf->register(new PolishedBlackstoneBrickWall(new BID(533, 0, -278),"Polished Blackstone Brick Wall", new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);

        $bf->register(new StoneButton(new BID(551, 0, -296), "Polished Blackstone Button" ,new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
        $bf->register(new WoodenButton(new BID(515, 0, -260), "Crimson Button" ,new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
        $bf->register(new WoodenButton(new BID(516, 0, -261), "Warped Button" ,new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
        $bf->register(new PolishedBlackstonePressurePlate(new BID(550, 0, -295), "Polished Blackstone Pressure Plate", new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
        $bf->register(new CrimsonPressurePlate(new BID(517, 0, -262), "Crimson Pressure Plate", new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
        $bf->register(new WarpedPressurePlate(new BID(518, 0, -263), "Warped Pressure Plate", new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
        //$bf->register(new CrimsonStandingSign(new BID(505, 0, -250), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
        //$bf->register(new WarpedStandingSign(new BID(506, 0, -251), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
        //$bf->register(new CrimsonWallSign(new BID(507, 0, -252), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
        //$bf->register(new WarpedWallSign(new BID(508, 0, -253), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
        //$bf->register(new LightBlock(new BID(470, 0, -215), new BlockBreakInfo(0)), true);
    }
}