<?php

namespace deceitya\ShopAPI\form\enchantShop;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\database\EnchantShopAPI;
use deceitya\ShopAPI\form\element\EnchantSelectFormButton;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\lang\Translatable;
use pocketmine\Server;

class EnchantSelectForm extends SimpleForm {

    public function __construct(?string $error = "") {
        $enchant = [
            VanillaEnchantments::SHARPNESS(),
            VanillaEnchantments::EFFICIENCY(),
            VanillaEnchantments::SILK_TOUCH(),
            EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::FORTUNE),
            VanillaEnchantments::UNBREAKING(),
            VanillaEnchantments::POWER(),
        ];
        $api = EnchantShopAPI::getInstance();
        $this
            ->setTitle("Enchant Form")
            ->setText("§7せつめい\n{$error}");
        foreach ($enchant as $value) {
            $enchantName = null;
            if ($value->getName() instanceof Translatable) {
                $enchantName = Server::getInstance()->getLanguage()->translate($value->getName());
            }
            $this->addElement(new EnchantSelectFormButton("{$enchantName} 価格 - 毎lv.{$api->getBuy($value)}\nMiningLevel制限{$api->getMiningLevel($value)} | 付与レベル制限 - {$api->getLimit($value)}以下", $value));
        }
    }

}