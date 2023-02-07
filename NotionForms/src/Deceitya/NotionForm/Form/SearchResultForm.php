<?php

declare(strict_types=1);

namespace Deceitya\NotionForm\Form;

use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\form\Form;
use pocketmine\player\Player;

class SearchResultForm implements Form {

	public $heading = [];
	public $index = [];
	public $searchdefault = [];
	private array $file;

	public function __construct(array $file, array $heading, array $searchdefault = []) {
		$this->file = $file;
		$this->heading = $heading;
		$this->searchdefault = $searchdefault;
	}

	public function handleResponse(Player $player, $data) : void {
		if ($data === null) {
			return;
		}
		if ($data === 0) {
			SendForm::Send($player, (new SearchForm($this->file, "", $this->searchdefault)));
			return;
		}
		if (!isset($this->index[$data - 1])) {
			SendMessage::Send($player, "エラーが発生しました", "NotionForm", false);
			return;
		}
		$id = $this->index[$data - 1];
		SendForm::Send($player, (new SearchContentForm($this->file, $id, $this->heading, $this->searchdefault)));
	}

	public function jsonSerialize() {
		$form = [
			'type' => 'form',
			'title' => 'Notion Form',
			'content' => '見たいコンテンツを選択してください',
			'buttons' => [
				['text' => "戻る"],
			],
		];
		foreach ($this->heading as $key => $heading) {
			$form['buttons'][] = ['text' => $heading];
			$this->index[] = $key;
		}
		return $form;
	}
}
