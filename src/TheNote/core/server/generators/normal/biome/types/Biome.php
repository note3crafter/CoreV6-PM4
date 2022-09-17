<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\generators\normal\biome\types;

use czechpmdevs\multiworld\generator\normal\populator\Populator;

abstract class Biome extends \pocketmine\world\biome\Biome {

	private bool $isFrozen;

	public function __construct(float $temperature, float $rainfall) {
		$this->temperature = $temperature;
		$this->rainfall = $rainfall;

		$this->isFrozen = ($temperature <= 0);
	}

	public function isFrozen(): bool {
		return $this->isFrozen;
	}

	public function setFrozen(bool $isFrozen = true): void {
		$this->isFrozen = $isFrozen;
	}

	/**
	 * @param Populator[] $populators
	 */
	public function addPopulators(array $populators = []): void {
		foreach ($populators as $populator) {
			$this->addPopulator($populator);
		}
	}
}