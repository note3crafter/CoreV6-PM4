<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\generators\normal;

use TheNote\core\server\generators\normal\biome\Badlands;
use TheNote\core\server\generators\normal\biome\BadlandsPlateau;
use TheNote\core\server\generators\normal\biome\Beach;
use TheNote\core\server\generators\normal\biome\BirchForest;
use TheNote\core\server\generators\normal\biome\DeepOcean;
use TheNote\core\server\generators\normal\biome\Desert;
use TheNote\core\server\generators\normal\biome\DesertHills;
use TheNote\core\server\generators\normal\biome\ExtremeHills;
use TheNote\core\server\generators\normal\biome\ExtremeHillsEdge;
use TheNote\core\server\generators\normal\biome\ExtremeHillsMutated;
use TheNote\core\server\generators\normal\biome\Forest;
use TheNote\core\server\generators\normal\biome\ForestHills;
use TheNote\core\server\generators\normal\biome\FrozenOcean;
use TheNote\core\server\generators\normal\biome\FrozenRiver;
use TheNote\core\server\generators\normal\biome\IceMountains;
use TheNote\core\server\generators\normal\biome\IcePlains;
use TheNote\core\server\generators\normal\biome\Jungle;
use TheNote\core\server\generators\normal\biome\MushroomIsland;
use TheNote\core\server\generators\normal\biome\MushroomIslandShore;
use TheNote\core\server\generators\normal\biome\Ocean;
use TheNote\core\server\generators\normal\biome\Plains;
use TheNote\core\server\generators\normal\biome\River;
use TheNote\core\server\generators\normal\biome\RoffedForestHills;
use TheNote\core\server\generators\normal\biome\RoofedForest;
use TheNote\core\server\generators\normal\biome\Savanna;
use TheNote\core\server\generators\normal\biome\SavannaPlateau;
use TheNote\core\server\generators\normal\biome\SunflowerPlains;
use TheNote\core\server\generators\normal\biome\Swampland;
use TheNote\core\server\generators\normal\biome\Taiga;
use TheNote\core\server\generators\normal\biome\TaigaHills;
use TheNote\core\server\generators\normal\biome\TallBirchForest;
use TheNote\core\server\generators\normal\biome\types\Biome;
use TheNote\core\server\generators\BiomeIds;
use InvalidStateException;
use function array_key_exists;

class BiomeFactory implements BiomeIds {

	private static BiomeFactory $instance;

	/** @var Biome[] */
	private array $biomes = [];

	public function registerBiome(int $id, Biome $biome): void {
		$biome->setId($id);

		$this->biomes[$id] = $biome;
	}

	public function getBiome(int $id): Biome {
		if (!array_key_exists($id, $this->biomes)) {
			throw new InvalidStateException("Biome with id $id is not registered.");
		}

		return $this->biomes[$id];
	}

	private static function init(): void {
		BiomeFactory::$instance = new self;

		BiomeFactory::$instance->registerBiome(BiomeIds::OCEAN, new Ocean());
		BiomeFactory::$instance->registerBiome(BiomeIds::PLAINS, new Plains());
		BiomeFactory::$instance->registerBiome(BiomeIds::DESERT, new Desert());
		BiomeFactory::$instance->registerBiome(BiomeIds::EXTREME_HILLS, new ExtremeHills());
		BiomeFactory::$instance->registerBiome(BiomeIds::FOREST, new Forest());
		BiomeFactory::$instance->registerBiome(BiomeIds::TAIGA, new Taiga());
		BiomeFactory::$instance->registerBiome(BiomeIds::SWAMP, new Swampland());
		BiomeFactory::$instance->registerBiome(BiomeIds::RIVER, new River());
		BiomeFactory::$instance->registerBiome(BiomeIds::FROZEN_OCEAN, new FrozenOcean());
		BiomeFactory::$instance->registerBiome(BiomeIds::FROZEN_RIVER, new FrozenRiver());
		BiomeFactory::$instance->registerBiome(BiomeIds::ICE_PLAINS, new IcePlains());
		BiomeFactory::$instance->registerBiome(BiomeIds::ICE_MOUNTAINS, new IceMountains());
		BiomeFactory::$instance->registerBiome(BiomeIds::MUSHROOM_ISLAND, new MushroomIsland());
		BiomeFactory::$instance->registerBiome(BiomeIds::MUSHROOM_ISLAND_SHORE, new MushroomIslandShore());
		BiomeFactory::$instance->registerBiome(BiomeIds::BEACH, new Beach());
		BiomeFactory::$instance->registerBiome(BiomeIds::DESERT_HILLS, new DesertHills());
		BiomeFactory::$instance->registerBiome(BiomeIds::FOREST_HILLS, new ForestHills());
		BiomeFactory::$instance->registerBiome(BiomeIds::TAIGA_HILLS, new TaigaHills());
		BiomeFactory::$instance->registerBiome(BiomeIds::EXTREME_HILLS_EDGE, new ExtremeHillsEdge());
		BiomeFactory::$instance->registerBiome(BiomeIds::JUNGLE, new Jungle());
		// TODO: Ids 21 - 23
		BiomeFactory::$instance->registerBiome(BiomeIds::DEEP_OCEAN, new DeepOcean());
		// TODO: Ids 25 - 26
		BiomeFactory::$instance->registerBiome(BiomeIds::BIRCH_FOREST, new BirchForest());
		// TODO: Id 28
		BiomeFactory::$instance->registerBiome(BiomeIds::ROOFED_FOREST, new RoofedForest());
		// TODO Ids 30 - 34
		BiomeFactory::$instance->registerBiome(BiomeIds::SAVANNA, new Savanna());
		BiomeFactory::$instance->registerBiome(BiomeIds::SAVANNA_PLATEAU, new SavannaPlateau());
		BiomeFactory::$instance->registerBiome(BiomeIds::BADLANDS, new Badlands());
		BiomeFactory::$instance->registerBiome(BiomeIds::BADLANDS_PLATEAU, new BadlandsPlateau());
		// TODO Ids 39 - 128
		BiomeFactory::$instance->registerBiome(BiomeIds::SUNFLOWER_PLAINS, new SunflowerPlains());
		// TODO Id 130
		BiomeFactory::$instance->registerBiome(BiomeIds::EXTREME_HILLS_MUTATED, new ExtremeHillsMutated());
		// TODO Ids 132 - 154
		BiomeFactory::$instance->registerBiome(BiomeIds::TALL_BIRCH_FOREST, new TallBirchForest());
		// TODO Id 156
		BiomeFactory::$instance->registerBiome(BiomeIds::ROOFED_FOREST_HILLS, new RoffedForestHills());
	}

	public static function getInstance(): BiomeFactory {
		if (!isset(BiomeFactory::$instance)) {
			BiomeFactory::init();
		}

		return BiomeFactory::$instance;
	}
}