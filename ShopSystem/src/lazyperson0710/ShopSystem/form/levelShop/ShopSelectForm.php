<?php

namespace lazyperson0710\ShopSystem\form\levelShop;

use bbo51dog\bboform\form\SimpleForm;
use Deceitya\MiningLevel\MiningLevelAPI;
use lazyperson0710\ShopSystem\form\levelShop\element\SendMenuFormButton;
use lazyperson0710\ShopSystem\form\levelShop\future\LevelConfirmation;
use lazyperson0710\ShopSystem\form\levelShop\future\RestrictionShop;
use lazyperson0710\ShopSystem\form\levelShop\future\ShopText;
use lazyperson0710\ShopSystem\form\levelShop\other\OtherShopFunctionSelectForm;
use lazyperson0710\ShopSystem\form\levelShop\temp\shop1\Shop1Form;
use lazyperson0710\ShopSystem\form\levelShop\temp\shop2\Shop2Form;
use lazyperson0710\ShopSystem\form\levelShop\temp\shop3\Shop3Form;
use lazyperson0710\ShopSystem\form\levelShop\temp\shop4\Shop4Form;
use lazyperson0710\ShopSystem\form\levelShop\temp\shop5\Shop5Form;
use lazyperson0710\ShopSystem\form\levelShop\temp\shop6\Shop6Form;
use lazyperson0710\ShopSystem\form\levelShop\temp\shop7\Shop7Form;
use pocketmine\player\Player;

class ShopSelectForm extends SimpleForm implements ShopText {

	public function __construct(Player $player, ?string $error = null) {
		$this
			->setTitle(self::TITLE)
			->setText(self::CONTENT . PHP_EOL . $error);
		foreach (RestrictionShop::ALL_SHOP as $shopNumber) {
			switch ($shopNumber) {
				case 0:
					$content = 'Other - 一括売却 & 検索 [ID: 0]';
					$form = new OtherShopFunctionSelectForm();
					$restrictionLevel = RestrictionShop::RESTRICTION_LEVEL_OTHER_SHOP;
					break;
				case 1:
					$content = 'Level Shop - 1 [ID: 1]';
					$restrictionLevel = RestrictionShop::RESTRICTION_LEVEL_SHOP_1;
					$form = LevelConfirmation::getInstance()->levelConfirmation($player, new CategorySelectForm(1), $restrictionLevel);
					break;
				case 2:
					$content = 'Level Shop - 2 [ID: 2]';
					$restrictionLevel = RestrictionShop::RESTRICTION_LEVEL_SHOP_2;
					break;
				case 3:
					$content = 'Level Shop - 3 [ID: 3]';
					$restrictionLevel = RestrictionShop::RESTRICTION_LEVEL_SHOP_3;
					break;
				case 4:
					$content = 'Level Shop - 4 [ID: 4]';
					$restrictionLevel = RestrictionShop::RESTRICTION_LEVEL_SHOP_4;
					break;
				case 5:
					$content = 'Level Shop - 5 [ID: 5]';
					$restrictionLevel = RestrictionShop::RESTRICTION_LEVEL_SHOP_5;
					break;
				case 6:
					$content = 'Level Shop - 6 [ID: 6]';
					$restrictionLevel = RestrictionShop::RESTRICTION_LEVEL_SHOP_6;
					break;
				case 7:
					$content = 'Level Shop - 7 [ID: 7]';
					$restrictionLevel = RestrictionShop::RESTRICTION_LEVEL_SHOP_7;
					break;
				default:
					throw new \RuntimeException('Invalid shop number');
			};
			if (MiningLevelAPI::getInstance()->getLevel($player) >= $restrictionLevel) {
				$restrictionLevelMessage = "§d解放済み / 要求レベル -> lv.{$restrictionLevel}";
			} else {
				$restrictionLevelMessage = "§c{$restrictionLevel}レベル以上で開放されます";
			}
			$this->addElement(new SendMenuFormButton($content . PHP_EOL . $restrictionLevelMessage, $restrictionLevel));
		}
	}

}