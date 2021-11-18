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

use pocketmine\block\Block;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockFactory as VBF;
use pocketmine\block\BlockIdentifier as BID;
use pocketmine\block\BlockLegacyIds as Ids;
use pocketmine\block\BlockToolType;
use pocketmine\block\Clay;
use pocketmine\block\Opaque;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;

class BlockManager
{



	public static function init()
	{

			//$parent = VanillaBlocks::SPONGE();
			//$parent_id_info = $parent->getIdInfo();
			//VBF::getInstance()->register(new Sponge(), true);
		 VBF::getInstance()->register(new WaxedExposedCopper(new BID(600, 0), "Waxed Exposed Copper", new BlockBreakInfo(0.6, BlockToolType::PICKAXE)));


		/*BlockFactory::register(new EndPortalFrame(), true);
		BlockFactory::register(new FrostedIce(), true);
		BlockFactory::register(new Cauldron(), true);
		//BlockFactory::register(new Sponge(), true);
		BlockFactory::register(new BrewingStand, true);
		BlockFactory::register(new EndPortal(), true);
		BlockFactory::register(new Portal(), true);
		BlockFactory::register(new Obsidian(), true);
		BlockFactory::register(new ShulkerBox(), true);
		BlockFactory::register(new UndyedShulkerBox(), true);
		BlockFactory::register(new Jukebox(), true);
		BlockFactory::register(new Beacon(), true);
		BlockFactory::register(new Anvil(), true);
		BlockFactory::register(new SkullBlock(), true);
		//BlockFactory::register(new NoteBlock(), true);
		BlockFactory::register(new RedstoneWire(), true);
		BlockFactory::register(new RedstoneRepeaterPowered(), true);
		BlockFactory::register(new RedstoneRepeaterUnpowered(), true);
		BlockFactory::register(new RedstoneComparatorPowered(), true);
		BlockFactory::register(new RedstoneComparatorUnpowered(), true);
		BlockFactory::register(new RedstoneTorch(), true);
		BlockFactory::register(new RedstoneTorchUnlit(), true);
		BlockFactory::register(new Redstone(), true);
		BlockFactory::register(new Lever(), true);
		BlockFactory::register(new ButtonStone(), true);
		BlockFactory::register(new ButtonWooden(), true);
		BlockFactory::register(new PressurePlateStone(), true);
		BlockFactory::register(new PressurePlateWooden(), true);
		BlockFactory::register(new WeightedPressurePlateLight(), true);
		BlockFactory::register(new WeightedPressurePlateHeavy(), true);
		BlockFactory::register(new DaylightDetector(), true);
		BlockFactory::register(new DaylightDetectorInverted(), true);
		BlockFactory::register(new Observer(), true);
		BlockFactory::register(new TrappedChest(), true);
		BlockFactory::register(new TripwireHook(), true);
		BlockFactory::register(new Tripwire(), true);
		BlockFactory::register(new RedstoneLamp(), true);
		BlockFactory::register(new RedstoneLampLit(), true);
		BlockFactory::register(new NoteBlockR(), true);
		BlockFactory::register(new Dropper(), true);
		BlockFactory::register(new Dispenser(), true);
		BlockFactory::register(new Hopper(), true);
		BlockFactory::register(new Piston(), true);
		BlockFactory::register(new Pistonarmcollision(), true);
		BlockFactory::register(new PistonSticky(), true);
		BlockFactory::register(new Moving(), true);
		BlockFactory::register(new CommandBlock(), true);
		BlockFactory::register(new CommandBlockRepeating(), true);
		BlockFactory::register(new CommandBlockChain(), true);
		BlockFactory::register(new TNT(), true);
		BlockFactory::register(new WoodenDoor(Block::OAK_DOOR_BLOCK, 0, "Oak Door", Item::OAK_DOOR), true);
		BlockFactory::register(new WoodenDoor(Block::SPRUCE_DOOR_BLOCK, 0, "Spruce Door", Item::SPRUCE_DOOR), true);
		BlockFactory::register(new WoodenDoor(Block::BIRCH_DOOR_BLOCK, 0, "Birch Door", Item::BIRCH_DOOR), true);
		BlockFactory::register(new WoodenDoor(Block::JUNGLE_DOOR_BLOCK, 0, "Jungle Door", Item::JUNGLE_DOOR), true);
		BlockFactory::register(new WoodenDoor(Block::ACACIA_DOOR_BLOCK, 0, "Acacia Door", Item::ACACIA_DOOR), true);
		BlockFactory::register(new WoodenDoor(Block::DARK_OAK_DOOR_BLOCK, 0, "Dark Oak Door", Item::DARK_OAK_DOOR), true);
		BlockFactory::register(new Trapdoor(VanillaBlocks::OAK_TRAPDOOR()), true);
		BlockFactory::register(new IronTrapdoor(), true);
		BlockFactory::register(new FenceGate(Block::OAK_FENCE_GATE, 0, "Oak Fence Gate"), true);
		BlockFactory::register(new FenceGate(Block::SPRUCE_FENCE_GATE, 0, "Spruce Fence Gate"), true);
		BlockFactory::register(new FenceGate(Block::BIRCH_FENCE_GATE, 0, "Birch Fence Gate"), true);
		BlockFactory::register(new FenceGate(Block::JUNGLE_FENCE_GATE, 0, "Jungle Fence Gate"), true);
		BlockFactory::register(new FenceGate(Block::DARK_OAK_FENCE_GATE, 0, "Dark Oak Fence Gate"), true);
		BlockFactory::register(new FenceGate(Block::ACACIA_FENCE_GATE, 0, "Acacia Fence Gate"), true);
		BlockFactory::registerBlock(new Slime(), true);*/

	}
}
