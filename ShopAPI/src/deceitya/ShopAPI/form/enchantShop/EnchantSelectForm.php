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
        $enchants = [
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
            ->setText("§7せつめい。。。\n{$error}");
        foreach ($enchants as $enchantment) {
            $enchantName = $enchantment->getName();
            if ($enchantName instanceof Translatable) {
                $enchantName = Server::getInstance()->getLanguage()->translate($enchantName);
            }
            $this->addElement(new EnchantSelectFormButton("{$enchantName} 価格 - 毎lv.{$api->getBuy($enchantName)}\nMiningLevel制限{$api->getMiningLevel($enchantName)} | 付与レベル制限 - {$api->getLimit($enchantName)}以下", $enchantment, $enchantName));
        }
    }

}