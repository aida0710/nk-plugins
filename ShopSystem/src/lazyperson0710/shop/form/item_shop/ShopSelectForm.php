<?php

declare(strict_types = 1);

namespace lazyperson0710\shop\form\item_shop;

use bbo51dog\bboform\form\SimpleForm;
use Deceitya\MiningLevel\MiningLevelAPI;
use lazyperson0710\shop\form\item_shop\element\SendCategorySelectFormButton;
use lazyperson0710\shop\form\item_shop\future\FormText;
use lazyperson0710\shop\form\item_shop\future\RestrictionShop;
use pocketmine\player\Player;
use RuntimeException;
use const PHP_EOL;

class ShopSelectForm extends SimpleForm {

    public function __construct(Player $player, ?string $error = null) {
        $this
            ->setTitle(FormText::TITLE)
            ->setText('コンテンツを選択してください' . PHP_EOL . '(/shop [ID]で実行すると直接formを開けます)' . $error);
        foreach (RestrictionShop::ALL_SHOP as $shopNumber) {
            switch ($shopNumber) {
                case 0:
                    $content = 'Other - 一括売却 & 検索 [ID: other]';
                    $restrictionLevel = RestrictionShop::RESTRICTION_LEVEL_OTHER_SHOP;
                    $shopNumber = 0;
                    break;
                case 1:
                    $content = 'Level Shop - 1 [ID: 1]';
                    $restrictionLevel = RestrictionShop::RESTRICTION_LEVEL_SHOP_1;
                    $shopNumber = 1;
                    break;
                case 2:
                    $content = 'Level Shop - 2 [ID: 2]';
                    $restrictionLevel = RestrictionShop::RESTRICTION_LEVEL_SHOP_2;
                    $shopNumber = 2;
                    break;
                case 3:
                    $content = 'Level Shop - 3 [ID: 3]';
                    $restrictionLevel = RestrictionShop::RESTRICTION_LEVEL_SHOP_3;
                    $shopNumber = 3;
                    break;
                case 4:
                    $content = 'Level Shop - 4 [ID: 4]';
                    $restrictionLevel = RestrictionShop::RESTRICTION_LEVEL_SHOP_4;
                    $shopNumber = 4;
                    break;
                case 5:
                    $content = 'Level Shop - 5 [ID: 5]';
                    $restrictionLevel = RestrictionShop::RESTRICTION_LEVEL_SHOP_5;
                    $shopNumber = 5;
                    break;
                case 6:
                    $content = 'Level Shop - 6 [ID: 6]';
                    $restrictionLevel = RestrictionShop::RESTRICTION_LEVEL_SHOP_6;
                    $shopNumber = 6;
                    break;
                case 7:
                    $content = 'Level Shop - 7 [ID: 7]';
                    $restrictionLevel = RestrictionShop::RESTRICTION_LEVEL_SHOP_7;
                    $shopNumber = 7;
                    break;
                default:
                    throw new RuntimeException('Invalid shop number');
            }
            if (MiningLevelAPI::getInstance()->getLevel($player) >= $restrictionLevel) {
                $restrictionLevelMessage = "§d解放済み / 要求レベル -> lv.{$restrictionLevel}";
            } else {
                $restrictionLevelMessage = "§c{$restrictionLevel}レベル以上で開放されます";
            }
            $this->addElement(new SendCategorySelectFormButton($content . PHP_EOL . $restrictionLevelMessage, $shopNumber, $restrictionLevel));
        }
    }

}
