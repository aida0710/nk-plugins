<?php

declare(strict_types = 0);

namespace nkserver\ranking\libs\form;

use pocketmine\player\Player;
use function is_int;

class SimpleForm extends BaseForm {

	public function addButton(string $text, ?string $image = null) {
		$button = ['text' => $text];
		if ($image !== null) {
			$button['image'] = [
				'type' => 'path',
				'data' => $image,
			];
		}
		$this->contents[] = $button;
	}

	final public function handleResponse(Player $player, $data) : void {
		if (!is_int($data)) {
			$this->receiveIllegalData($player, $data);
			return;
		}
		$this->onSubmit($player, $data);
	}

	final public function jsonSerialize() {
		$json = [
			'type' => self::SIMPLE,
			'title' => $this->title,
		];
		$json['content'] = $this->label;
		$json['buttons'] = $this->contents;
		return $json;
	}
}
