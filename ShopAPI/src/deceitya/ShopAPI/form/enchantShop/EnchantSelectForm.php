<?php

namespace deceitya\ShopAPI\form\enchantShop;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\database\EnchantShopAPI;
use deceitya\ShopAPI\form\element\EnchantSelectFormButton;
use pocketmine\item\enchantment\VanillaEnchantments;

class EnchantSelectForm extends SimpleForm {

    public function __construct(?string $error = "") {
        /**
         *  "text" => "",
         * "options" => [
         * "タップして付与したいエンチャントを選択してください",
         * "ダメージ増加 | 5lv以下 | Lv/3000円",
         * "効率強化 | 5lv以下 | Lv/5000円",
         * "シルクタッチ | 1lv以下 | Lv/15000円",
         * "幸運 | 3lv以下 | Lv/30000円",
         * "耐久力 | 3lv以下 | Lv/10000円",
         * "射撃ダメージ増加 | 5lv以下 | Lv/30000円"
         * ],
         */
        $api = EnchantShopAPI::getInstance();
        $this
            ->setTitle("Enchant Form")
            ->setText("§7Enchant名 | nLv以下(最大レベル) | Lv/n(レベルごとの値段){$error}")
            ->addElements(
                new EnchantSelectFormButton("耐久力 価格 - 毎lv.{$api->getBuy(VanillaEnchantments::UNBREAKING())}\nMiningLevel制限{$api->getMiningLevel(VanillaEnchantments::UNBREAKING())} | 付与レベル制限 - {$api->getLimit(VanillaEnchantments::UNBREAKING())}以下", VanillaEnchantments::UNBREAKING())
            );
    }

}