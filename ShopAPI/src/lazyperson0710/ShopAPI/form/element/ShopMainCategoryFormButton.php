<?php

declare(strict_types = 0);

namespace lazyperson0710\ShopSystem\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use bbo51dog\bboform\form\FormBase;
use deceitya\MiningLevel\MiningLevelAPI;
use lazyperson0710\ShopSystem\database\LevelShopAPI;
use lazyperson0710\ShopSystem\form\levelShop\MainLevelShopForm;
use lazyperson0710\ShopSystem\form\levelShop\other\OtherShopFunctionSelectForm;
use lazyperson0710\ShopSystem\form\levelShop\shop1\Shop1Form;
use lazyperson0710\ShopSystem\form\levelShop\shop2\Shop2Form;
use lazyperson0710\ShopSystem\form\levelShop\shop3\Shop3Form;
use lazyperson0710\ShopSystem\form\levelShop\shop4\Shop4Form;
use lazyperson0710\ShopSystem\form\levelShop\shop5\Shop5Form;
use lazyperson0710\ShopSystem\form\levelShop\shop6\Shop6Form;
use lazyperson0710\ShopSystem\form\levelShop\shop7\Shop7Form;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\player\Player;

class ShopMainCategoryFormButton extends Button {

	private FormBase $class;

	public function __construct(string $text, FormBase $class, ?ButtonImage $image = null) {
		parent::__construct($text, $image);
		$this->class = $class;
	}

	public function handleSubmit(Player $player) : void {
		$level = match ($this->class::class) {
			OtherShopFunctionSelectForm::class => LevelShopAPI::RESTRICTION_LEVEL_OTHER_SHOP,
			Shop1Form::class => LevelShopAPI::RESTRICTION_LEVEL_SHOP_1,
			Shop2Form::class => LevelShopAPI::RESTRICTION_LEVEL_SHOP_2,
			Shop3Form::class => LevelShopAPI::RESTRICTION_LEVEL_SHOP_3,
			Shop4Form::class => LevelShopAPI::RESTRICTION_LEVEL_SHOP_4,
			Shop5Form::class => LevelShopAPI::RESTRICTION_LEVEL_SHOP_5,
			Shop6Form::class => LevelShopAPI::RESTRICTION_LEVEL_SHOP_6,
			Shop7Form::class => LevelShopAPI::RESTRICTION_LEVEL_SHOP_7,
			default => 0,
		};
		if (MiningLevelAPI::getInstance()->getLevel($player) >= $level) {
			SendForm::Send($player, ($this->class));
		} else {
			$error = "§c要求されたレベルに達していない為処理が中断されました\n要求レベル -> lv.{$level}";
			SendForm::Send($player, (new MainLevelShopForm($player, $error)));
			SoundPacket::Send($player, 'dig.chain');
		}
	}
}
