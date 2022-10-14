<?php

namespace lazyperson0710\ShopAPI\form\levelShop;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\MiningLevel\MiningLevelAPI;
use lazyperson0710\ShopAPI\database\LevelShopAPI;
use lazyperson0710\ShopAPI\form\element\ShopMainCategoryFormButton;
use lazyperson0710\ShopAPI\form\levelShop\other\OtherShopFunctionSelectForm;
use lazyperson0710\ShopAPI\form\levelShop\shop1\Shop1Form;
use lazyperson0710\ShopAPI\form\levelShop\shop2\Shop2Form;
use lazyperson0710\ShopAPI\form\levelShop\shop3\Shop3Form;
use lazyperson0710\ShopAPI\form\levelShop\shop4\Shop4Form;
use lazyperson0710\ShopAPI\form\levelShop\shop5\Shop5Form;
use lazyperson0710\ShopAPI\form\levelShop\shop6\Shop6Form;
use lazyperson0710\ShopAPI\form\levelShop\shop7\Shop7Form;
use pocketmine\player\Player;

class MainLevelShopForm extends SimpleForm {

    public function __construct(Player $player, ?string $error = null) {
        $levelShopList = [
            LevelShopAPI::RestrictionLevel_OtherShop => new OtherShopFunctionSelectForm(),
            LevelShopAPI::RestrictionLevel_Shop1 => new Shop1Form(),
            LevelShopAPI::RestrictionLevel_Shop2 => new Shop2Form(),
            LevelShopAPI::RestrictionLevel_Shop3 => new Shop3Form(),
            LevelShopAPI::RestrictionLevel_Shop4 => new Shop4Form(),
            LevelShopAPI::RestrictionLevel_Shop5 => new Shop5Form(),
            LevelShopAPI::RestrictionLevel_Shop6 => new Shop6Form(),
            LevelShopAPI::RestrictionLevel_Shop7 => new Shop7Form(),
        ];
        $this
            ->setTitle("Level Shop")
            ->setText("見たいコンテンツを選択してください\n{$error}");
        foreach ($levelShopList as $restrictionLevel => $levelShop) {
            $content = match ($levelShop::class) {
                OtherShopFunctionSelectForm::class => 'Other - 一括売却 & 検索',
                Shop1Form::class => "Level Shop - 1",
                Shop2Form::class => "Level Shop - 2",
                Shop3Form::class => "Level Shop - 3",
                Shop4Form::class => "Level Shop - 4",
                Shop5Form::class => "Level Shop - 5",
                Shop6Form::class => "Level Shop - 6",
                Shop7Form::class => "Level Shop - 7",
                default => "Unknown",
            };
            if (MiningLevelAPI::getInstance()->getLevel($player) >= $restrictionLevel) {
                $restrictionLevelMessage = "§a解放済み / 要求レベル -> lv.{$restrictionLevel}";
            } else {
                $restrictionLevelMessage = "§c{$restrictionLevel}レベル以上で開放されます";
            }
            $this->addElements(
                new ShopMainCategoryFormButton("{$content}\n{$restrictionLevelMessage}", $levelShop),
            );
        }
    }
}