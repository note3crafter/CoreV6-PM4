<?php

namespace TheNote\core\player;

use pocketmine\player\Player;

class CustomPlayer extends Player{

    public string $username;
    public string $displayName;
    public string $iusername;

    public function getName(): string{
		$username = $this->username;
		if($this->hasSpaces($username)){
			$username = str_replace(" ", "_", $username);
			$this->username = $username;
			$this->displayName = $username;
			$this->iusername = strtolower($username);
			return $username;
		}
		return $username;
	}
	public function getDisplayName(): string{
		$displayName = $this->displayName;
		if($this->hasSpaces($displayName)){
			$displayName = str_replace(" ", "_", $displayName);
			$this->username = $displayName;
			$this->displayName = $displayName;
			$this->iusername = strtolower($displayName);
			return $displayName;
		}
		return $displayName;
	}
	public function getLowerCaseName(): string{
		$iusername = $this->iusername;
		if($this->hasSpaces($iusername)){
			$iusername = str_replace(" ", "_", $iusername);
			$this->username = $iusername;
			$this->displayName = $iusername;
			$this->iusername = strtolower($iusername);
			return $iusername;
		}
		return $iusername;
	}
	private function hasSpaces(string $string): bool{
		return str_contains($string, ' ');
	}
}
