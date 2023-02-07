<?php

declare(strict_types=1);

namespace lazyperson0710\ShopAPI\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use bbo51dog\bboform\form\FormBase;
use deceitya\MiningLevel\MiningLevelAPI;
use lazyperson0710\ShopAPI\database\LevelShopAPI;
use lazyperson0710\ShopAPI\form\levelShop\MainLevelShopForm;
use lazyperson0710\ShopAPI\form\levelShop\other\OtherShopFunctionSelectForm;
use lazyperson0710\ShopAPI\form\levelShop\shop1\Shop1Form;
use lazyperson0710\ShopAPI\form\levelShop\shop2\Shop2Form;
use lazyperson0710\ShopAPI\form\levelShop\shop3\Shop3Form;
use lazyperson0710\ShopAPI\form\levelShop\shop4\Shop4Form;
use lazyperson0710\ShopAPI\form\levelShop\shop5\Shop5Form;
use lazyperson0710\ShopAPI\form\levelShop\shop6\Shop6Form;
use lazyperson0710\ShopAPI\form\levelShop\shop7\Shop7Form;
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
			OtherShopFunctionSelectForm::class => LevelShopAPI::RestrictionLevel_OtherShop,
			Shop1Form::class => LevelShopAPI::RestrictionLevel_Shop1,
			Shop2Form::class => LevelShopAPI::RestrictionLevel_Shop2,
			Shop3Form::class => LevelShopAPI::RestrictionLevel_Shop3,
			Shop4Form::class => LevelShopAPI::RestrictionLevel_Shop4,
			Shop5Form::class => LevelShopAPI::RestrictionLevel_Shop5,
			Shop6Form::class => LevelShopAPI::RestrictionLevel_Shop6,
			Shop7Form::class => LevelShopAPI::RestrictionLevel_Shop7,
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
