<?php

declare(strict_types = 0);
namespace rib\tw\form;

use pocketmine\form\Form;
use pocketmine\player\Player;
use rib\tw\level\World;

class TeleportForm implements Form {

	private $world;
	private $worlds_array = [];

	public function __construct() {
		$this->world = new World();
		$this->worlds_array = $this->world->getAllWorldName();
	}

	public function handleResponse(Player $player, $data) : void {
		if ($data === null) return;
		$this->world->teleport($player, $this->worlds_array[$data + 2]);
	}

	public function jsonSerialize() : array {
		return [
			'type' => 'form',
			'title' => 'WorldUI',
			'content' => '選択してください',
			'buttons' => $this->conversionArray($this->worlds_array),
		];
	}

	private function conversionArray(array $array) : array {
		$result = [];
		foreach ($array as $value) {
			$result[] = ['text' => $value];
		}
		return $result;
	}

}
