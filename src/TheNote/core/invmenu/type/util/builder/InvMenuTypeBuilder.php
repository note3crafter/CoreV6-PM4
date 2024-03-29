<?php

declare(strict_types=1);

namespace TheNote\core\invmenu\type\util\builder;

use TheNote\core\invmenu\type\InvMenuType;

interface InvMenuTypeBuilder{

	public function build() : InvMenuType;
}