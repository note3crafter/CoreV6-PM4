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
use pocketmine\block\BlockIdentifierFlattened;
use pocketmine\block\BlockLegacyIds as Ids;
use pocketmine\block\BlockToolType;
use pocketmine\block\UnknownBlock;
use pocketmine\item\ItemIds;
use pocketmine\item\ToolTier;
use pocketmine\network\mcpe\convert\RuntimeBlockMapping;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use ReflectionMethod;
use TheNote\core\Main;
use const pocketmine\BEDROCK_DATA_PATH;
use const pocketmine\LOCALE_DATA_PATH;
use const pocketmine\RESOURCE_PATH;

class BlockManager
{
	use SingletonTrait;
	public function __construct()
	{
		self::setInstance($this);

		//$this->plugin = $plugin;
	}
	public function startup(): void
	{
		//VBF::getInstance()->register(new Lantern(new BID(ItemIds::LANTERN, 0), new BlockBreakInfo(5, BlockToolType::AXE,  ToolTier::WOOD()->getHarvestLevel())), true);

		VBF::getInstance()->register(new Campfire(new BID(464, 0, -211), new BlockBreakInfo(2, BlockToolType::AXE, 2)), true);
		//VBF::getInstance()->register(new LavaCauldron(new BID(465, 0, 465), new BlockBreakInfo(0.6, BlockToolType::NONE, 0.9)), true);
		//VBF::getInstance()->register(new Campfire(new BID(466, 0, -211), new BlockBreakInfo(3600000, BlockToolType::NONE, -1)), true);

		VBF::getInstance()->register(new Composter(new BID(468, 0, -213), new BlockBreakInfo(0.6, BlockToolType::NONE, 0.9)), true);

		VBF::getInstance()->register(new LightBlock(new BID(470, 0, -215), new BlockBreakInfo(0)), true);
		VBF::getInstance()->register(new WhiterRose(new BID(471, 0, -216), new BlockBreakInfo(0)), true);

		VBF::getInstance()->register(new BeeNest(new BID(473, 0, -218), new BlockBreakInfo(0.3, BlockToolType::AXE, 0.3)), true);
		VBF::getInstance()->register(new Beehive(new BID(474, 0, -219,), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.6)), true);
		VBF::getInstance()->register(new HoneyBlock(new BID(475, 0, -220), new BlockBreakInfo(0)), true);
		VBF::getInstance()->register(new HoneyCombBlock(new BID(476, 0, -221), new BlockBreakInfo(0.6)), true);
		VBF::getInstance()->register(new Lodestone(new BID(477, 0, -222), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new CrimsonRoots(new BID(478, 0, -223), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new WarpedRoots(new BID(479, 0, -224), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new CrimsonStem(new BID(480, 0, -225), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new WarpedStem(new BID(481, 0, -226), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new WarpedWartBlock(new BID(482, 0, -227), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new CrimsonFungus(new BID(483, 0, -228), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new WarpedFungus(new BID(484, 0, -229), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new ShroomLight(new BID(485, 0, -230), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new WeepingVines(new BID(486, 0, -231), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new CrimsonNylium(new BID(487, 0, -232), new BlockBreakInfo(0.6, BlockToolType::SHOVEL, 0.9)), true);
		VBF::getInstance()->register(new WarpedNylium(new BID(488, 0, -233), new BlockBreakInfo(0.6, BlockToolType::SHOVEL, 0.9)), true);
		VBF::getInstance()->register(new Basalt(new BID(489, 0, -234), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new PolishedBasalt(new BID(490, 0, -235), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new SoulSoil(new BID(491, 0, -236), new BlockBreakInfo(0.6, BlockToolType::SHOVEL, 0.9)), true);
		VBF::getInstance()->register(new SoulFire(new BID(492, 0, -237), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new NetherSprouts(new BID(493, 0, -238), new BlockBreakInfo(0.6, BlockToolType::SWORD, 0.9)), true);
		VBF::getInstance()->register(new Target(new BID(494, 0, -239), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new StrippedCrimsonStem(new BID(495, 0, -240), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new StrippedWarpedStem(new BID(496, 0, -241), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new CrimsonPlanks(new BID(497, 0, -242), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new WarpedPlanks(new BID(498, 0, -243), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new CrimsonDoor(new BID(499, 0, -244), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new WarpedDoor(new BID(500, 0, -245), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new CrimsonTrapdoor(new BID(501, 0, -246), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new WarpedTrapdoor(new BID(502, 0, -247), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new CrimsonStandingSign(new BID(505, 0, -250), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new WarpedStandingSign(new BID(506, 0, -251), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new CrimsonWallSign(new BID(507, 0, -252), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new WarpedWallSign(new BID(508, 0, -253), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new CrimsonStairs(new BID(509, 0, -254), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new WarpedStairs(new BID(510, 0, -255), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new CrimsonFence(new BID(511, 0, -256), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new WarpedFence(new BID(512, 0, -257), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new CrimsonFenceGate(new BID(513, 0, -258), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new WarpedFenceGate(new BID(514, 0, -259), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new CrimsonButton(new BID(515, 0, -260), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new WarpedButton(new BID(516, 0, -261), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new CrimsonPressurePlate(new BID(517, 0, -262), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new WarpedPressurePlate(new BID(518, 0, -263), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new CrimsonSlab(new BID(519, 0, -264), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new WarpedSlab(new BID(520, 0, -265), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new CrimsonDoubleSlab(new BID(521, 0, -266), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new WarpedDoubleSlab(new BID(522, 0, -267), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new SoulTorch(new BID(523, 0, -268), new BlockBreakInfo(0.6, BlockToolType::NONE, 0.9)), true);
		VBF::getInstance()->register(new SoulLantern(new BID(524, 0, -269), new BlockBreakInfo(0.6, BlockToolType::NONE, 0.9)), true);
		VBF::getInstance()->register(new NetheriteBlock(new BID(525, 0, -270), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new AncientDebris(new BID(526, 0, -271), new BlockBreakInfo(0.6, BlockToolType::NONE, 0.9)), true);
		VBF::getInstance()->register(new RespawnAnchor(new BID(527, 0, -272), new BlockBreakInfo(0.6, BlockToolType::NONE, 0.9)), true);
		VBF::getInstance()->register(new Blackstone(new BID(528, 0, -273), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new PolishedBlackstoneBricks(new BID(529, 0, -274), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new PolishedBlackstoneBrickStairs(new BID(530, 0, -275), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new BlackstoneStairs(new BID(531, 0, -276), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new BlackstoneWall(new BID(532, 0, -277), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new PolishedBlackstoneBrickWall(new BID(533, 0, -278), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new ChiseledPolishedBlackstone(new BID(534, 0, -279), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new CrackedPolishedBlackstoneBricks(new BID(535, 0, -280), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new BlackstoneSlab(new  BID(536,0, -281), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new GildedBlackstone(new BID(537, 0, -282), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new BlackstoneDoubleSlab(new BID(538, 0, -283), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new PolishedBlackstoneBrickSlab(new BID(539, 0, -284), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new PolishedBlackstoneBrickDoubleSlab(new BID(540, 0, -285), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new Chain(new BID(541, 0, -286), new BlockBreakInfo(5, BlockToolType::PICKAXE, 0)), true);
		VBF::getInstance()->register(new TwistingVines(new BID(542, 0, -287), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new NetherGoldOre(new BID(543, 0, -288), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new CryingObsidian(new BID(544, 0, -289), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new SoulCampfire(new BID(545, 0, -290), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new PolishedBlackstone(new BID(546, 0, -291), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new PolishedBlackstoneStairs(new BID(547, 0, -292), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new PolishedBlackstoneSlab(new BID(548, 0, -293), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new PolishedBlackstoneDoubleSlab(new BID(549, 0, -294), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new PolishedBlackstonePressurePlate(new BID(550, 0, -295), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new PolishedBlackstoneButton(new BID(551, 0, -296), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new PolishedBlackstoneWall(new BID(552, 0, -297), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new WarpedHyphae(new BID(553, 0, -298), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new CrimsonHyphae(new BID(554, 0, -299), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new StrippedCrimsonHyphae(new BID(555, 0, -300), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new StrippedWarpedHyphae(new BID(556, 0, -301), new BlockBreakInfo(0.6, BlockToolType::AXE, 0.9)), true);
		VBF::getInstance()->register(new ChiseledNetherBricks(new BID(557, 0, -302), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new CrackedNetherBricks(new BID(558, 0, -303), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
		VBF::getInstance()->register(new QuartzBricks(new BID(559, 0, -304), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);

		VBF::getInstance()->register(new CopperBlock(new BID(595, 0, 595), new BlockBreakInfo(6, BlockToolType::PICKAXE, 3)), true);
		/*VBF::getInstance()->register(new ExposedCopper	(new BID(596, 0, 596), new BlockBreakInfo(6, BlockToolType::PICKAXE, 3)), true);
		VBF::getInstance()->register(new WeatheredCopper(new BID(597, 0, 597), new BlockBreakInfo(6, BlockToolType::PICKAXE, 3)), true);
		VBF::getInstance()->register(new OxidizedCopper(new BID(598, 0, 598), new BlockBreakInfo(6, BlockToolType::PICKAXE, 3)), true);
		VBF::getInstance()->register(new WaxedCopper(new BID(599, 0, 599), new BlockBreakInfo(6, BlockToolType::PICKAXE, 3)), true);
		VBF::getInstance()->register(new WaxedExposedCopper(new BID(600, 0, 600), new BlockBreakInfo(6, BlockToolType::PICKAXE, 3)), true);
		VBF::getInstance()->register(new WaxedWeatheredCopper(new BID(601, 0, 601), new BlockBreakInfo(6, BlockToolType::PICKAXE, 3)), true);
		VBF::getInstance()->register(new CutCopper(new BID(602, 0, 602), new BlockBreakInfo(6, BlockToolType::PICKAXE, 3)), true);
		VBF::getInstance()->register(new ExposedCutCopper(new BID(603, 0, 603), new BlockBreakInfo(6, BlockToolType::PICKAXE, 3)), true);
		VBF::getInstance()->register(new WeatheredCutCopper	(new BID(604, 0, 604), new BlockBreakInfo(6, BlockToolType::PICKAXE, 3)), true);
		VBF::getInstance()->register(new OxidizedCutCopper(new BID(605, 0, 605), new BlockBreakInfo(6, BlockToolType::PICKAXE, 3)), true);
		VBF::getInstance()->register(new WaxedCutCopper(new BID(606, 0, 606), new BlockBreakInfo(6, BlockToolType::PICKAXE, 3)), true);
		VBF::getInstance()->register(new WaxedExposedCutCopper(new BID(607, 0, 607), new BlockBreakInfo(6, BlockToolType::PICKAXE, 3)), true);
		VBF::getInstance()->register(new WaxedWeatheredCutCopper	(new BID(608, 0, 608), new BlockBreakInfo(6, BlockToolType::PICKAXE, 3)), true);

		VBF::getInstance()->register(new WaxedOxidizedCopper(new BID(701, 0, 701), new BlockBreakInfo(6, BlockToolType::PICKAXE, 3)), true);
		VBF::getInstance()->register(new WaxedOxidizedCutCopper(new BID(702, 0, 702), new BlockBreakInfo(6 BlockToolType::PICKAXE, 3)), true);*/

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

		$blockIdMap = json_decode(file_get_contents(BEDROCK_DATA_PATH . 'block_id_map.json'), true);
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


/*Block of Copper	minecraft:copper_block
Copper Ingot	Copper Ingot	minecraft:copper_ingot
Copper Ore	Copper Ore	minecraft:copper_ore
Cut Copper	Cut Copper	minecraft:cut_copper
Cut Copper Slab	Cut Copper Slab	minecraft:cut_copper_slab
Cut Copper Stairs	Cut Copper Stairs	minecraft:cut_copper_stairs
Deepslate Copper Ore	Deepslate Copper Ore	minecraft:deepslate_copper_ore
Exposed Copper	Exposed Copper	minecraft:exposed_copper
Exposed Cut Copper	Exposed Cut Copper	minecraft:exposed_cut_copper
Exposed Cut Copper Slab	Exposed Cut Copper Slab	minecraft:exposed_cut_copper_slab
Exposed Cut Copper Stairs	Exposed Cut Copper Stairs	minecraft:exposed_cut_copper_stairs
Oxidized Copper	Oxidized Copper	minecraft:oxidized_copper
Oxidized Cut Copper	Oxidized Cut Copper	minecraft:oxidized_cut_copper
Oxidized Cut Copper Slab	Oxidized Cut Copper Slab	minecraft:oxidized_cut_copper_slab
Oxidized Cut Copper Stairs	Oxidized Cut Copper Stairs	minecraft:oxidized_cut_copper_stairs
Raw Copper	Raw Copper	minecraft:raw_copper
Block of Raw Copper	Block of Raw Copper	minecraft:raw_copper_block
Waxed Block of Copper	Waxed Block of Copper	minecraft:waxed_copper_block
Waxed Cut Copper	minecraft:waxed_cut_copper
Waxed Cut Copper Slab	minecraft:waxed_cut_copper_slab
Waxed Cut Copper Stairs	minecraft:waxed_cut_copper_stairs
Waxed Exposed Copper	minecraft:waxed_exposed_copper
Waxed Exposed Cut Copper	minecraft:waxed_exposed_cut_copper
Waxed Exposed Cut Copper Slab	minecraft:waxed_exposed_cut_copper_slab
Waxed Exposed Cut Copper Stairs	minecraft:waxed_exposed_cut_copper_stairs
Waxed Oxidized Copper	minecraft:waxed_oxidized_copper
Waxed Oxidized Cut Copper	minecraft:waxed_oxidized_cut_copper
Waxed Oxidized Cut Copper Slab	minecraft:waxed_oxidized_cut_copper_slab
Waxed Oxidized Cut Copper Stairs	minecraft:waxed_oxidized_cut_copper_stairs
Waxed Weathered Copper	minecraft:waxed_weathered_copper
Waxed Weathered Cut Copper	minecraft:waxed_weathered_cut_copper
Waxed Weathered Cut Copper Slab	minecraft:waxed_weathered_cut_copper_slab
Waxed Weathered Cut Copper Stairs	minecraft:waxed_weathered_cut_copper_stairs
Weathered Copper	Weathered Copper	minecraft:weathered_copper
Weathered Cut Copper	Weathered Cut Copper	minecraft:weathered_cut_copper
Weathered Cut Copper Slab	Weathered Cut Copper Slab	minecraft:weathered_cut_copper_slab
Weathered Cut Copper Stairs	Weathered Cut Copper Stairs	minecraft:weathered_cut_copper_stairs*/



	/*public static function init()
	{

			//$parent = VanillaBlocks::SPONGE();
			//$parent_id_info = $parent->getIdInfo();
			//VBF::getInstance()->register(new Sponge(), true);
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
