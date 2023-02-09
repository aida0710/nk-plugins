<?php

declare(strict_types = 1);
namespace bbo51dog\mjolnir;

use pocketmine\utils\SingletonTrait;

class Setting {

	use SingletonTrait;

	private string $kickMessage;

	private string $defaultBanReason;

	public function setData(array $datas) : void {
		$this->kickMessage = $datas['messages']['kick-message'];
		$this->defaultBanReason = $datas['messages']['default-ban-reason'];
	}

	public function getKickMessage() : string {
		return $this->kickMessage;
	}

	public function getDefaultBanReason() : string {
		return $this->defaultBanReason;
	}
}
