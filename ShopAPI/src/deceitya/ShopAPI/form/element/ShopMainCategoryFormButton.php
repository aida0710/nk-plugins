<?php

namespace deceitya\ShopAPI\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use bbo51dog\bboform\form\FormBase;
use Deceitya\MiningLevel\MiningLevelAPI;
use deceitya\ShopAPI\database\LevelShopAPI;
use deceitya\ShopAPI\form\levelShop\MainLevelShopForm;
use deceitya\ShopAPI\form\levelShop\other\OtherShopFunctionSelectForm;
use deceitya\ShopAPI\form\levelShop\shop1\Shop1Form;
use deceitya\ShopAPI\form\levelShop\shop2\Shop2Form;
use deceitya\ShopAPI\form\levelShop\shop3\Shop3Form;
use deceitya\ShopAPI\form\levelShop\shop4\Shop4Form;
use deceitya\ShopAPI\form\levelShop\shop5\Shop5Form;
use deceitya\ShopAPI\form\levelShop\shop6\Shop6Form;
use deceitya\ShopAPI\form\levelShop\shop7\Shop7Form;
use lazyperson710\core\packet\SendForm;
use pocketmine\player\Player;

class ShopMainCategoryFormButton extends Button {

    private FormBase $class;

    /**
     * @param string           $text
     * @param FormBase         $class
     * @param ButtonImage|null $image
     */
    public function __construct(string $text, FormBase $class, ?ButtonImage $image = null) {
        parent::__construct($text, $image);
        $this->class = $class;
    }

    public function handleSubmit(Player $player): void {
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
        }
    }
}