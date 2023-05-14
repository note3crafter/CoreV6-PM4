<?php

declare(strict_types=1);

namespace TheNote\core\invmenu\session;

use TheNote\core\invmenu\InvMenu;
use TheNote\core\invmenu\type\graphic\InvMenuGraphic;

final class InvMenuInfo{

	public function __construct(
		public InvMenu $menu,
		public InvMenuGraphic $graphic,
		public ?string $graphic_name
	){}
}