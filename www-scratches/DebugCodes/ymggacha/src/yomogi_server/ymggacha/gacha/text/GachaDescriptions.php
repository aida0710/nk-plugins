<?php

declare(strict_types = 1);

namespace ymggacha\src\yomogi_server\ymggacha\gacha\text;

class GachaDescriptions {

	public static function getYmgGachaDescription() : string {
		return <<<DESCTIPTION
			エンチャツール排出！
			あなたの採掘と建築のお供に
			チケット: よもぎガチャチケット
			
			 1回: 1枚
			10回: 10枚
			DESCTIPTION;
	}
}
