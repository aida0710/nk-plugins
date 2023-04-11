<?php

declare(strict_types = 1);

namespace ymggacha\src\yomogi_server\ymggacha\gacha;

trait EmmitListTrait {

	private string $emmitList;

	public function getEmmitList() : string {
		return $this->emmitList;
	}
}
