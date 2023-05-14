<?php

declare(strict_types=1);

namespace TheNote\core\invmenu\type;

use TheNote\core\invmenu\inventory\InvMenuInventory;
use TheNote\core\invmenu\InvMenu;
use TheNote\core\invmenu\type\graphic\ActorInvMenuGraphic;
use TheNote\core\invmenu\type\graphic\InvMenuGraphic;
use TheNote\core\invmenu\type\graphic\network\InvMenuGraphicNetworkTranslator;
use pocketmine\inventory\Inventory;
use pocketmine\network\mcpe\protocol\types\entity\MetadataProperty;
use pocketmine\player\Player;

final class ActorFixedInvMenuType implements FixedInvMenuType{

	/**
	 * @param string $actor_identifier
	 * @param int $actor_runtime_identifier
	 * @param array<int, MetadataProperty> $actor_metadata
	 * @param int $size
	 * @param InvMenuGraphicNetworkTranslator|null $network_translator
	 */
	public function __construct(
		private string $actor_identifier,
		private int $actor_runtime_identifier,
		private array $actor_metadata,
		private int $size,
		private ?InvMenuGraphicNetworkTranslator $network_translator = null
	){}

	public function getSize() : int{
		return $this->size;
	}

	public function createGraphic(InvMenu $menu, Player $player) : ?InvMenuGraphic{
		return new ActorInvMenuGraphic($this->actor_identifier, $this->actor_runtime_identifier, $this->actor_metadata, $this->network_translator);
	}

	public function createInventory() : Inventory{
		return new InvMenuInventory($this->size);
	}
}