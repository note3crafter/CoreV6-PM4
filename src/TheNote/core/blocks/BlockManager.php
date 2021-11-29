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
use pocketmine\data\bedrock\LegacyBlockIdToStringIdMap;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\ItemIds;
use pocketmine\item\StringToItemParser;
use pocketmine\item\ToolTier;
use pocketmine\network\mcpe\convert\ItemTranslator;
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
		VBF::getInstance()->register(new BlackstoneSlab(new  BID(536, 0, -281), new BlockBreakInfo(0.6, BlockToolType::PICKAXE, 0.9)), true);
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

		//VBF::getInstance()->register(new CopperBlock(new BID(595, 0, 595), new BlockBreakInfo(6, BlockToolType::PICKAXE, 3)), true);
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
		//self::registerBlockGlobally(new CopperBlock(new BID(595, 0, -340), new BlockBreakInfo(3, BlockToolType::PICKAXE, -1)));

		BlockManager::registerBlockInNetworkLayer(-340);

		Server::getInstance()->getAsyncPool()->addWorkerStartHook(function (int $worker): void {
			Server::getInstance()->getAsyncPool()->submitTaskToWorker(new class() extends AsyncTask {

				public function onRun(): void
				{
					BlockManager::getInstance()->initializeRuntimeIds();
					BlockManager::getInstance()->registerRuntimeIds();
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
	##1.17 Blöcke
	public static function registerRuntimeIds(): void
	{
		$blocks = [
			'minecraft:copper_block' => 595,
			'minecraft:waxed_exposed_copper' => 600,
			'minecraft:waxed_weathered_copper' => 601,
			'minecraft:cut_copper' => 602,
		];
		$itemids = [
			'minecraft:copper_block' => -340,
			/*'minecraft:waxed_exposed_copper' => -345,
			'minecraft:waxed_weathered_copper' => 601,
			'minecraft:cut_copper' => 602,*/
		];

		$runtimeBlockMapping = RuntimeBlockMapping::getInstance();
		$registerMapping = new \ReflectionMethod($runtimeBlockMapping, 'registerMapping');
		$registerMapping->setAccessible(true);
		$metaMap = [];
		foreach ($runtimeBlockMapping->getBedrockKnownStates() as $legacyId => $nbt) {
			$mcpeName = $nbt->getString("name");
			$meta = isset($metaMap[$mcpeName]) ? ($metaMap[$mcpeName] + 1) : 0;
			$id = $blocks[$mcpeName] ?? Ids::AIR;
			$legacyId = $itemids[$nbt->getString('name')] ?? null;
			if ($legacyId === null) continue;

			$metaMap[$legacyId] ??= 0;
			if ($id !== Ids::AIR && $meta <= 15 && !VBF::getInstance()->isRegistered($id, $meta)) {
				$metaMap[$mcpeName] = $meta;
				$registerMapping->invoke($runtimeBlockMapping, $legacyId, $id, $meta);
			}

		}
		/*foreach ($runtimeBlockMapping->getBedrockKnownStates() as $runtimeId => $state) {
			$legacyId = $blocks[$state->getString('name')] ?? null;
			if ($legacyId === null) continue;

			$metaMap[$legacyId] ??= 0;

			$registerMapping->invoke($runtimeBlockMapping, $runtimeId, $legacyId, $metaMap[$legacyId]++);
		}*/

		$legacyBlockIdToStringIdMap = LegacyBlockIdToStringIdMap::getInstance();
		$runtimeBlockMapping = RuntimeBlockMapping::getInstance();
		$registerMapping = new \ReflectionMethod($runtimeBlockMapping, 'registerMapping');
		$registerMapping->setAccessible(true);
		$metaMap = [];
		foreach ($runtimeBlockMapping->getBedrockKnownStates() as $runtimeId => $state) {
			$legacyId = $legacyBlockIdToStringIdMap->stringToLegacy($state->getString("name"));
			if ($legacyId === null or $legacyId <= Ids::LIT_BLAST_FURNACE) {
				continue;
			}
		}
	}
	public static function registerBlockInNetworkLayer(int $itemId): void
	{
		$itemTranslator = ItemTranslator::getInstance();
		$prop = new \ReflectionProperty($itemTranslator, 'simpleCoreToNetMapping');
		$prop->setAccessible(true);
		$simpleCoreToNetMap = $prop->getValue($itemTranslator);
		$simpleCoreToNetMap[$itemId] = $itemId;
		$prop->setValue($itemTranslator, $simpleCoreToNetMap);

		$prop = new \ReflectionProperty($itemTranslator, 'simpleNetToCoreMapping');
		$prop->setAccessible(true);
		$simpleNetToCoreMap = $prop->getValue($itemTranslator);
		$simpleNetToCoreMap[$itemId] = $itemId;
		$prop->setValue($itemTranslator, $simpleNetToCoreMap);
	}

	/*public static function registerRuntimeIds(): void
	{
		$blocks = [
			'minecraft:copper_block' => 595,
			'minecraft:waxed_exposed_copper' => 600,
			'minecraft:waxed_weathered_copper' => 601,
			'minecraft:cut_copper' => 602,
		];

		$runtimeBlockMapping = RuntimeBlockMapping::getInstance();
		$registerMapping = new \ReflectionMethod($runtimeBlockMapping, 'registerMapping');
		$registerMapping->setAccessible(true);
		$metaMap = [];
		foreach ($runtimeBlockMapping->getBedrockKnownStates() as $runtimeId => $state) {
			$legacyId = $blocks[$state->getString('name')] ?? null;
			if ($legacyId === null) continue;

			$metaMap[$legacyId] ??= 0;

			$registerMapping->invoke($runtimeBlockMapping, $runtimeId, $legacyId, $metaMap[$legacyId]++);
		}

		$legacyBlockIdToStringIdMap = LegacyBlockIdToStringIdMap::getInstance();
		$runtimeBlockMapping = RuntimeBlockMapping::getInstance();
		$registerMapping = new \ReflectionMethod($runtimeBlockMapping, 'registerMapping');
		$registerMapping->setAccessible(true);
		$metaMap = [];
		foreach ($runtimeBlockMapping->getBedrockKnownStates() as $runtimeId => $state) {
			$legacyId = $legacyBlockIdToStringIdMap->stringToLegacy($state->getString("name"));
			if ($legacyId === null or $legacyId <= Ids::LIT_BLAST_FURNACE) {
				continue;
			}
		}
	}*/

	public static function registerBlockGlobally(Block $block): void
	{
		// API Layer
		VBF::getInstance()->register($block);
		CreativeInventory::getInstance()->add($block->asItem());
		StringToItemParser::getInstance()->registerBlock(strtolower(str_replace(' ', '_', $block->getName())), fn() => $block);

		// Network Layer
		$itemId = $block->getIdInfo()->getItemId();

		$itemTranslator = ItemTranslator::getInstance();
		$prop = new \ReflectionProperty($itemTranslator, 'simpleCoreToNetMapping');
		$prop->setAccessible(true);

		$simpleCoreToNetMap = $prop->getValue($itemTranslator);
		$simpleCoreToNetMap[$itemId] = $itemId;
		$prop->setValue($itemTranslator, $simpleCoreToNetMap);

		$prop = new \ReflectionProperty($itemTranslator, 'simpleNetToCoreMapping');
		$prop->setAccessible(true);
		$simpleNetToCoreMap = $prop->getValue($itemTranslator);
		$simpleNetToCoreMap[$itemId] = $itemId;
		$prop->setValue($itemTranslator, $simpleNetToCoreMap);

	}
}


/*"minecraft:unknown": 560,
    "minecraft:powder_snow": 561,
    "minecraft:sculk_sensor": 562,
    "minecraft:pointed_dripstone": 563,
    "minecraft:copper_ore": 566,
    "minecraft:lightning_rod": 567,
    "minecraft:dripstone_block": 572,
    "minecraft:dirt_with_roots": 573,
    "minecraft:hanging_roots": 574,
    "minecraft:moss_block": 575,
    "minecraft:spore_blossom": 576,
    "minecraft:cave_vines": 577,
    "minecraft:big_dripleaf": 578,
    "minecraft:azalea_leaves": 579,
    "minecraft:azalea_leaves_flowered": 580,
    "minecraft:calcite": 581,
    "minecraft:amethyst_block": 582,
    "minecraft:budding_amethyst": 583,
    "minecraft:amethyst_cluster": 584,
    "minecraft:large_amethyst_bud": 585,
    "minecraft:medium_amethyst_bud": 586,
    "minecraft:small_amethyst_bud": 587,
    "minecraft:tuff": 588,
    "minecraft:tinted_glass": 589,
    "minecraft:moss_carpet": 590,
    "minecraft:small_dripleaf_block": 591,
    "minecraft:azalea": 592,
    "minecraft:flowering_azalea": 593,
    "minecraft:glow_frame": 594,
    "minecraft:copper_block": 595,
    "minecraft:exposed_copper": 596,
    "minecraft:weathered_copper": 597,
    "minecraft:oxidized_copper": 598,
    "minecraft:waxed_copper": 599,
    "minecraft:waxed_exposed_copper": 600,
    "minecraft:waxed_weathered_copper": 601,
    "minecraft:cut_copper": 602,
    "minecraft:exposed_cut_copper": 603,
    "minecraft:weathered_cut_copper": 604,
    "minecraft:oxidized_cut_copper": 605,
    "minecraft:waxed_cut_copper": 606,
    "minecraft:waxed_exposed_cut_copper": 607,
    "minecraft:waxed_weathered_cut_copper": 608,
    "minecraft:cut_copper_stairs": 609,
    "minecraft:exposed_cut_copper_stairs": 610,
    "minecraft:weathered_cut_copper_stairs": 611,
    "minecraft:oxidized_cut_copper_stairs": 612,
    "minecraft:waxed_cut_copper_stairs": 613,
    "minecraft:waxed_exposed_cut_copper_stairs": 614,
    "minecraft:waxed_weathered_cut_copper_stairs": 615,
    "minecraft:cut_copper_slab": 616,
    "minecraft:exposed_cut_copper_slab": 617,
    "minecraft:weathered_cut_copper_slab": 618,
    "minecraft:oxidized_cut_copper_slab": 619,
    "minecraft:waxed_cut_copper_slab": 620,
    "minecraft:waxed_exposed_cut_copper_slab": 621,
    "minecraft:waxed_weathered_cut_copper_slab": 622,
    "minecraft:double_cut_copper_slab": 623,
    "minecraft:exposed_double_cut_copper_slab": 624,
    "minecraft:weathered_double_cut_copper_slab": 625,
    "minecraft:oxidized_double_cut_copper_slab": 626,
    "minecraft:waxed_double_cut_copper_slab": 627,
    "minecraft:waxed_exposed_double_cut_copper_slab": 628,
    "minecraft:waxed_weathered_double_cut_copper_slab": 629,
    "minecraft:cave_vines_body_with_berries": 630,
    "minecraft:cave_vines_head_with_berries": 631,
    "minecraft:smooth_basalt": 632,
    "minecraft:deepslate": 633,
    "minecraft:cobbled_deepslate": 634,
    "minecraft:cobbled_deepslate_slab": 635,
    "minecraft:cobbled_deepslate_stairs": 636,
    "minecraft:cobbled_deepslate_wall": 637,
    "minecraft:polished_deepslate": 638,
    "minecraft:polished_deepslate_slab": 639,
    "minecraft:polished_deepslate_stairs": 640,
    "minecraft:polished_deepslate_wall": 641,
    "minecraft:deepslate_tiles": 642,
    "minecraft:deepslate_tile_slab": 643,
    "minecraft:deepslate_tile_stairs": 644,
    "minecraft:deepslate_tile_wall": 645,
    "minecraft:deepslate_bricks": 646,
    "minecraft:deepslate_brick_slab": 647,
    "minecraft:deepslate_brick_stairs": 648,
    "minecraft:deepslate_brick_wall": 649,
    "minecraft:chiseled_deepslate": 650,
    "minecraft:cobbled_deepslate_double_slab": 651,
    "minecraft:polished_deepslate_double_slab": 652,
    "minecraft:deepslate_tile_double_slab": 653,
    "minecraft:deepslate_brick_double_slab": 654,
    "minecraft:deepslate_lapis_ore": 655,
    "minecraft:deepslate_iron_ore": 656,
    "minecraft:deepslate_gold_ore": 657,
    "minecraft:deepslate_redstone_ore": 658,
    "minecraft:lit_deepslate_redstone_ore": 659,
    "minecraft:deepslate_diamond_ore": 660,
    "minecraft:deepslate_coal_ore": 661,
    "minecraft:deepslate_emerald_ore": 662,
    "minecraft:deepslate_copper_ore": 663,
    "minecraft:cracked_deepslate_tiles": 664,
    "minecraft:cracked_deepslate_bricks": 665,
    "minecraft:glow_lichen": 666,
    "minecraft:candle": 667,
    "minecraft:white_candle": 668,
    "minecraft:orange_candle": 669,
    "minecraft:magenta_candle": 670,
    "minecraft:light_blue_candle": 671,
    "minecraft:yellow_candle": 672,
    "minecraft:lime_candle": 673,
    "minecraft:pink_candle": 674,
    "minecraft:gray_candle": 675,
    "minecraft:light_gray_candle": 676,
    "minecraft:cyan_candle": 677,
    "minecraft:purple_candle": 678,
    "minecraft:blue_candle": 679,
    "minecraft:brown_candle": 680,
    "minecraft:green_candle": 681,
    "minecraft:red_candle": 682,
    "minecraft:black_candle": 683,
    "minecraft:candle_cake": 684,
    "minecraft:white_candle_cake": 685,
    "minecraft:orange_candle_cake": 686,
    "minecraft:magenta_candle_cake": 687,
    "minecraft:light_blue_candle_cake": 688,
    "minecraft:yellow_candle_cake": 689,
    "minecraft:lime_candle_cake": 690,
    "minecraft:pink_candle_cake": 691,
    "minecraft:gray_candle_cake": 692,
    "minecraft:light_gray_candle_cake": 693,
    "minecraft:cyan_candle_cake": 694,
    "minecraft:purple_candle_cake": 695,
    "minecraft:blue_candle_cake": 696,
    "minecraft:brown_candle_cake": 697,
    "minecraft:green_candle_cake": 698,
    "minecraft:red_candle_cake": 699,
    "minecraft:black_candle_cake": 700,
    "minecraft:waxed_oxidized_copper": 701,
    "minecraft:waxed_oxidized_cut_copper": 702,
    "minecraft:waxed_oxidized_cut_copper_stairs": 703,
    "minecraft:waxed_oxidized_cut_copper_slab": 704,
    "minecraft:waxed_oxidized_double_cut_copper_slab": 705,
    "minecraft:raw_iron_block": 706,
    "minecraft:raw_copper_block": 707,
    "minecraft:raw_gold_block": 708,
    "minecraft:infested_deepslate": 709,
    "minecraft:sculk": 713,
    "minecraft:sculk_vein": 714,
    "minecraft:sculk_catalyst": 715,
    "minecraft:sculk_shrieker": 716,
    "minecraft:client_request_placeholder_block": 720,
    "minecraft:mysterious_frame": 721,
    "minecraft:mysterious_frame_slot": 722

IDLIST
"minecraft:client_request_placeholder_block": -465,
    "minecraft:infested_deepslate": -454,
    "minecraft:raw_gold_block": -453,
    "minecraft:raw_copper_block": -452,
    "minecraft:raw_iron_block": -451,
    "minecraft:waxed_oxidized_double_cut_copper_slab": -450,
    "minecraft:waxed_oxidized_cut_copper_slab": -449,
    "minecraft:waxed_oxidized_cut_copper_stairs": -448,
    "minecraft:waxed_oxidized_cut_copper": -447,
    "minecraft:waxed_oxidized_copper": -446,
    "minecraft:black_candle_cake": -445,
    "minecraft:red_candle_cake": -444,
    "minecraft:green_candle_cake": -443,
    "minecraft:brown_candle_cake": -442,
    "minecraft:blue_candle_cake": -441,
    "minecraft:purple_candle_cake": -440,
    "minecraft:cyan_candle_cake": -439,
    "minecraft:light_gray_candle_cake": -438,
    "minecraft:gray_candle_cake": -437,
    "minecraft:pink_candle_cake": -436,
    "minecraft:lime_candle_cake": -435,
    "minecraft:yellow_candle_cake": -434,
    "minecraft:light_blue_candle_cake": -433,
    "minecraft:magenta_candle_cake": -432,
    "minecraft:orange_candle_cake": -431,
    "minecraft:white_candle_cake": -430,
    "minecraft:candle_cake": -429,
    "minecraft:black_candle": -428,
    "minecraft:red_candle": -427,
    "minecraft:green_candle": -426,
    "minecraft:brown_candle": -425,
    "minecraft:blue_candle": -424,
    "minecraft:purple_candle": -423,
    "minecraft:cyan_candle": -422,
    "minecraft:light_gray_candle": -421,
    "minecraft:gray_candle": -420,
    "minecraft:pink_candle": -419,
    "minecraft:lime_candle": -418,
    "minecraft:yellow_candle": -417,
    "minecraft:light_blue_candle": -416,
    "minecraft:magenta_candle": -415,
    "minecraft:orange_candle": -414,
    "minecraft:white_candle": -413,
    "minecraft:candle": -412,
    "minecraft:glow_lichen": -411,
    "minecraft:cracked_deepslate_bricks": -410,
    "minecraft:cracked_deepslate_tiles": -409,
    "minecraft:deepslate_copper_ore": -408,
    "minecraft:deepslate_emerald_ore": -407,
    "minecraft:deepslate_coal_ore": -406,
    "minecraft:deepslate_diamond_ore": -405,
    "minecraft:lit_deepslate_redstone_ore": -404,
    "minecraft:deepslate_redstone_ore": -403,
    "minecraft:deepslate_gold_ore": -402,
    "minecraft:deepslate_iron_ore": -401,
    "minecraft:deepslate_lapis_ore": -400,
    "minecraft:deepslate_brick_double_slab": -399,
    "minecraft:deepslate_tile_double_slab": -398,
    "minecraft:polished_deepslate_double_slab": -397,
    "minecraft:cobbled_deepslate_double_slab": -396,
    "minecraft:chiseled_deepslate": -395,
    "minecraft:deepslate_brick_wall": -394,
    "minecraft:deepslate_brick_stairs": -393,
    "minecraft:deepslate_brick_slab": -392,
    "minecraft:deepslate_bricks": -391,
    "minecraft:deepslate_tile_wall": -390,
    "minecraft:deepslate_tile_stairs": -389,
    "minecraft:deepslate_tile_slab": -388,
    "minecraft:deepslate_tiles": -387,
    "minecraft:polished_deepslate_wall": -386,
    "minecraft:polished_deepslate_stairs": -385,
    "minecraft:polished_deepslate_slab": -384,
    "minecraft:polished_deepslate": -383,
    "minecraft:cobbled_deepslate_wall": -382,
    "minecraft:cobbled_deepslate_stairs": -381,
    "minecraft:cobbled_deepslate_slab": -380,
    "minecraft:cobbled_deepslate": -379,
    "minecraft:deepslate": -378,
    "minecraft:smooth_basalt": -377,
    "minecraft:cave_vines_head_with_berries": -376,
    "minecraft:cave_vines_body_with_berries": -375,
    "minecraft:waxed_weathered_double_cut_copper_slab": -374,
    "minecraft:waxed_exposed_double_cut_copper_slab": -373,
    "minecraft:waxed_double_cut_copper_slab": -372,
    "minecraft:oxidized_double_cut_copper_slab": -371,
    "minecraft:weathered_double_cut_copper_slab": -370,
    "minecraft:exposed_double_cut_copper_slab": -369,
    "minecraft:double_cut_copper_slab": -368,
    "minecraft:waxed_weathered_cut_copper_slab": -367,
    "minecraft:waxed_exposed_cut_copper_slab": -366,
    "minecraft:waxed_cut_copper_slab": -365,
    "minecraft:oxidized_cut_copper_slab": -364,
    "minecraft:weathered_cut_copper_slab": -363,
    "minecraft:exposed_cut_copper_slab": -362,
    "minecraft:cut_copper_slab": -361,
    "minecraft:waxed_weathered_cut_copper_stairs": -360,
    "minecraft:waxed_exposed_cut_copper_stairs": -359,
    "minecraft:waxed_cut_copper_stairs": -358,
    "minecraft:oxidized_cut_copper_stairs": -357,
    "minecraft:weathered_cut_copper_stairs": -356,
    "minecraft:exposed_cut_copper_stairs": -355,
    "minecraft:cut_copper_stairs": -354,
    "minecraft:waxed_weathered_cut_copper": -353,
    "minecraft:waxed_exposed_cut_copper": -352,
    "minecraft:waxed_cut_copper": -351,
    "minecraft:oxidized_cut_copper": -350,
    "minecraft:weathered_cut_copper": -349,
    "minecraft:exposed_cut_copper": -348,
    "minecraft:cut_copper": -347,
    "minecraft:waxed_weathered_copper": -346,
    "minecraft:waxed_exposed_copper": -345,
    "minecraft:waxed_copper": -344,
    "minecraft:oxidized_copper": -343,
    "minecraft:weathered_copper": -342,
    "minecraft:exposed_copper": -341,
    "minecraft:copper_block": -340,
    "minecraft:glow_frame": -339,
    "minecraft:flowering_azalea": -338,
    "minecraft:azalea": -337,
    "minecraft:small_dripleaf_block": -336,
    "minecraft:moss_carpet": -335,
    "minecraft:tinted_glass": -334,
    "minecraft:tuff": -333,
    "minecraft:small_amethyst_bud": -332,
    "minecraft:medium_amethyst_bud": -331,
    "minecraft:large_amethyst_bud": -330,
    "minecraft:amethyst_cluster": -329,
    "minecraft:budding_amethyst": -328,
    "minecraft:amethyst_block": -327,
    "minecraft:calcite": -326,
    "minecraft:azalea_leaves_flowered": -325,
    "minecraft:azalea_leaves": -324,
    "minecraft:big_dripleaf": -323,
    "minecraft:cave_vines": -322,
    "minecraft:spore_blossom": -321,
    "minecraft:moss_block": -320,
    "minecraft:hanging_roots": -319,
    "minecraft:dirt_with_roots": -318,
    "minecraft:dripstone_block": -317,
    "minecraft:lightning_rod": -312,
    "minecraft:copper_ore": -311,
    "minecraft:pointed_dripstone": -308,
    "minecraft:sculk_sensor": -307,
    "minecraft:powder_snow": -306,
    "minecraft:unknown": -305,


*/





