<?php

namespace deceitya\ShopAPI\form\enchantShop;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use Deceitya\MiningLevel\MiningLevelAPI;
use deceitya\ShopAPI\database\EnchantShopAPI;
use onebone\economyapi\EconomyAPI;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\player\Player;

class EnchantBuyForm extends CustomForm {

    private int $level;
    private Enchantment $enchantment;
    private string $enchantName;

    public function __construct(Player $player, int $level, Enchantment $enchantment, string $enchantName) {
        $this->level = $level;
        $this->enchantment = $enchantment;
        $this->enchantName = $enchantName;
        $this
            ->setTitle("Enchant Form")
            ->addElements(
                new Label("現在の所持金 -> " . EconomyAPI::getInstance()->myMoney($player) . "円\n"),
                new Label("購入価格 -> " . EnchantShopAPI::getInstance()->getBuy($this->enchantment) * $this->level . "円"),
                new Label("{$enchantName}を{$level}レベル付与しますか？\n"),
                new Label("§c注意 : エンチャントレベルは上書きされます(1lvを二度付与しても2lvにはならず1lvになります)§r"),
                new Label("所持しているアイテム -> " . $player->getInventory()->getItemInHand()->getName()),
            );
    }

    public function handleSubmit(Player $player): void {
        $price = EnchantShopAPI::getInstance()->getBuy($this->enchantment) * $this->level;
        if (EconomyAPI::getInstance()->myMoney($player) <= $price) {
            $player->sendMessage("§bEnchant §7>> §c所持金が足りません。要求価格 -> {$price}円");
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) < EnchantShopAPI::getInstance()->getMiningLevel($this->enchantment)) {
            $player->sendForm(new EnchantSelectForm("§cMiningLevelが足りないためformを開けませんでした\n要求レベル ->" . EnchantShopAPI::getInstance()->getMiningLevel($this->enchantment) . "lv"));
            return;
        }
        EconomyAPI::getInstance()->reduceMoney($player, $price);
        $player->getInventory()->addItem($player->getInventory()->getItemInHand()->addEnchantment(new EnchantmentInstance($this->enchantment, $this->level)));
        $player->sendMessage("§bEnchant §7>> §a{$this->enchantName}を{$this->level}レベルで付与しました");
    }

}