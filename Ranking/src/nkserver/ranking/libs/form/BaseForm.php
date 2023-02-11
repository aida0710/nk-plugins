<?php

declare(strict_types = 0);
namespace nkserver\ranking\libs\form;

use pocketmine\form\Form;
use pocketmine\player\Player;
use function is_callable;

abstract class BaseForm implements Form {

	public const SIMPLE = 'form';
	public const MODAL = 'modal';
	public const CUSTOM = 'custom_form';
	public string $title = '';
	public string $label = '';
	/** @var ?callable */
	public $submit = null;
	/** @var ?callable */
	public $cancelled = null;
	/** @var ?callable */
	public $illegal = null;
	protected string $type;
	protected array $contents = [];

	protected function receiveIllegalData(Player $player) : void {
		if (!is_callable($this->illegal)) return;
		($this->illegal)($player);
	}

	protected function onSubmit(Player $player, $data) : void {
		if ($data === null) {
			$this->onCancelled($player);
			return;
		}
		if (!is_callable($this->submit)) return;
		($this->submit)($player, $data);
	}

	protected function onCancelled(Player $player) : void {
		if (!is_callable($this->cancelled)) return;
		($this->cancelled)($player);
	}
}
