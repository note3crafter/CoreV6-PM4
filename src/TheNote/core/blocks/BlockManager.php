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
use pocketmine\block\BlockFactory as VBF;
use pocketmine\block\BlockIdentifier as BID;
use pocketmine\block\BlockLegacyIds as Ids;
use pocketmine\block\BlockToolType;
use pocketmine\network\mcpe\convert\RuntimeBlockMapping;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use ReflectionMethod;
use TheNote\core\Main;
use const pocketmine\RESOURCE_PATH;

class BlockManager
{
	use SingletonTrait;

	public function __construct()
	{
		self::setInstance($this);
	}

	/*public static function init(): void
	{

		VBF::getInstance()->register(new WaxedExposedCopper(new BID(600, 0, 600), "Waxed Exposed Copper", new BlockBreakInfo(0.6, BlockToolType::PICKAXE)));
		$instance = RuntimeBlockMapping::getInstance();
		$method = new ReflectionMethod(RuntimeBlockMapping::class, "registerMapping");
		$method->setAccessible(true);
		$blockIdMap = json_decode(file_get_contents(Main::getInstance()->getDataFolder() . 'block_id_map.json'), true);
		$metaMap = [];
		foreach ($instance->getBedrockKnownStates() as $runtimeId => $nbt) {
			$mcpeName = $nbt->getString("name");
			$meta = isset($metaMap[$mcpeName]) ? ($metaMap[$mcpeName] + 1) : 0;
			$id = $blockIdMap[$mcpeName] ?? Ids::AIR;

			if ($id !== Ids::AIR && $meta <= 15 && !VBF::getInstance()->isRegistered($id, $meta)) {
				//var_dump("Runtime: $runtimeId Id: $id Name: $mcpeName Meta $meta");
				$metaMap[$mcpeName] = $meta;
				$method->invoke($instance, $runtimeId, $id, $meta);
			}
		}
	}*/
	public function startup(): void
	{
		VBF::getInstance()->register(new HoneyCombBlock(new BID(476, 0, 476), new BlockBreakInfo(0.6, BlockToolType::NONE, 0.9)), true);
		VBF::getInstance()->register(new HoneyBlock(new BID(475, 0, 475), new BlockBreakInfo(0.6, BlockToolType::NONE, 0.9)), true);
		VBF::getInstance()->register(new Basalt(new BID(489, 0, 489), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new PolishedBasalt(new BID(490, 0, 490), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new SoulSoil(new BID(491, 0, 491), new BlockBreakInfo(0.6, BlockToolType::SHOVEL, 0.9)), true);
		VBF::getInstance()->register(new StrippedCrimsonStem(new BID(495, 0, 495), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new StrippedWarpedStem(new BID(496, 0, 496), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new CrimsonPlanks(new BID(497, 0, 497), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new WarpedPlanks(new BID(498, 0, 498), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new CrimsonDoor(new BID(499, 0, 499), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new WarpedDoor(new BID(500, 0, 500), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new CrimsonTrapdoor(new BID(501, 0, 501), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new WarpedTrapdoor(new BID(502, 0, 502), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new CrimsonStandingSign(new BID(505, 0, 505), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new WarpedStandingSign(new BID(506, 0, 506), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new CrimsonWallSign(new BID(507, 0, 507), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new WarpedWallSign(new BID(508, 0, 508), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new CrimsonStairs(new BID(509, 0, 509), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new WarpedStairs(new BID(510, 0, 510), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new CrimsonFence(new BID(511, 0, 511), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new WarpedFence(new BID(512, 0, 512), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new CrimsonFenceGate(new BID(513, 0, 513), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new WarpedFenceGate(new BID(514, 0, 514), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new CrimsonButton(new BID(515, 0, 515), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new WarpedButton(new BID(516, 0, 516), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new CrimsonPressurePlate(new BID(517, 0, 517), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new WarpedPressurePlate(new BID(518, 0, 518), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new CrimsonSlab(new BID(519, 0, 519), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new WarpedSlab(new BID(520, 0, 520), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new CrimsonDoubleSlab(new BID(521, 0, 521), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new WarpedDoubleSlab(new BID(522, 0, 522), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new SoulTorch(new BID(523, 0, 523), new BlockBreakInfo(0.6, BlockToolType::NONE, 0.9)), true);
		VBF::getInstance()->register(new SoulLantern(new BID(524, 0, 524), new BlockBreakInfo(0.6, BlockToolType::NONE, 0.9)), true);
		VBF::getInstance()->register(new NetheriteBlock(new BID(525, 0, 525), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new AncientDebris(new BID(526, 0, 526), new BlockBreakInfo(0.6, BlockToolType::NONE, 0.9)), true);
		VBF::getInstance()->register(new RespawnAnchor(new BID(527, 0, 527), new BlockBreakInfo(0.6, BlockToolType::NONE, 0.9)), true);
		VBF::getInstance()->register(new Blackstone(new BID(528, 0, 528), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new PolishedBlackstoneBricks(new BID(529, 0, 529), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new PolishedBlackstoneBrickStairs(new BID(530, 0, 530), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new BlackstoneStairs(new BID(531, 0, 531), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new BlackstoneWall(new BID(532, 0, 532), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
//		VBF::getInstance()->register(new CrimsonDoor(new BID(499, 0, 499), new BlockBreakInfo(0.6, BlockToolType::NONE, 0.9)), true);
//		VBF::getInstance()->register(new CrimsonDoor(new BID(499, 0, 499), new BlockBreakInfo(0.6, BlockToolType::NONE, 0.9)), true);
//		VBF::getInstance()->register(new CrimsonDoor(new BID(499, 0, 499), new BlockBreakInfo(0.6, BlockToolType::NONE, 0.9)), true);
//		VBF::getInstance()->register(new CrimsonDoor(new BID(499, 0, 499), new BlockBreakInfo(0.6, BlockToolType::NONE, 0.9)), true);


		Server::getInstance()->getAsyncPool()->addWorkerStartHook(function (int $worker): void {
			Server::getInstance()->getAsyncPool()->submitTaskToWorker(new class() extends AsyncTask {

				public function onRun(): void
				{
					BlockManager::getInstance()->initializeRuntimeIds();
				}
			}, $worker);
		});
	}

	public function initializeRuntimeIds(): void
	{
		$instance = RuntimeBlockMapping::getInstance();
		$method = new ReflectionMethod(RuntimeBlockMapping::class, "registerMapping");
		$method->setAccessible(true);

		$blockIdMap = json_decode(file_get_contents(\pocketmine\BEDROCK_DATA_PATH . 'block_id_map.json'), true);
		$metaMap = [];

		foreach ($instance->getBedrockKnownStates() as $runtimeId => $nbt) {
			$mcpeName = $nbt->getString("name");
			$meta = isset($metaMap[$mcpeName]) ? ($metaMap[$mcpeName] + 1) : 0;
			$id = $blockIdMap[$mcpeName] ?? Ids::AIR;

			if ($id !== Ids::AIR && $meta <= 15 && !VBF::getInstance()->isRegistered($id, $meta)) {
				//var_dump("Runtime: $runtimeId Id: $id Name: $mcpeName Meta $meta");
				$metaMap[$mcpeName] = $meta;
				$method->invoke($instance, $runtimeId, $id, $meta);
			}
		}
	}






	/*public static function init()
	{

			//$parent = VanillaBlocks::SPONGE();
			//$parent_id_info = $parent->getIdInfo();
			//VBF::getInstance()->register(new Sponge(), true);
		VBF::getInstance()->register(new WaxedExposedCopper(new BID(600, 0, 600), "Waxed Exposed Copper", new BlockBreakInfo(0.6, BlockToolType::PICKAXE)));
		VBF::getInstance()->register(new HoneyCombBlock(new BID(475, 0,  475), new BlockBreakInfo(0.6, BlockToolType::NONE,0.9)), true);


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
