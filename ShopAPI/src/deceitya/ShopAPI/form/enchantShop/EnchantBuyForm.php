<?php

namespace deceitya\ShopAPI\form\enchantShop;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use Deceitya\MiningLevel\MiningLevelAPI;
use deceitya\ShopAPI\database\EnchantShopAPI;
use onebone\economyapi\EconomyAPI;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\item\Durable;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
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
                new Label("購入価格 -> " . EnchantShopAPI::getInstance()->getBuy($enchantName) * $this->level . "円"),
                new Label("{$enchantName}を{$level}レベル付与しますか？\n"),
                new Label("§c注意 : エンチャントレベルは上書きされます(1lvを二度付与しても2lvにはならず1lvになります)§r"),
                new Label("所持しているアイテム -> " . $player->getInventory()->getItemInHand()->getName()),
            );
    }

    public function handleSubmit(Player $player): void {
        $price = EnchantShopAPI::getInstance()->getBuy($this->enchantName) * $this->level;
        $item = $player->getInventory()->getItemInHand();
        if (EconomyAPI::getInstance()->myMoney($player) <= $price) {
            $player->sendMessage("§bEnchant §7>> §c所持金が足りない為処理が中断されました。要求価格 -> {$price}円");
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) < EnchantShopAPI::getInstance()->getMiningLevel($this->enchantName)) {
            $player->sendForm(new EnchantSelectForm("§cMiningLevelが足りないためformを開けませんでした\n要求レベル ->" . EnchantShopAPI::getInstance()->getMiningLevel($this->enchantName) . "lv"));
            return;
        }
        if (!$player->getInventory()->getItemInHand() instanceof Durable) {
            $player->sendForm(new EnchantSelectForm("§cアイテムが不正です\nアイテム -> " . $player->getInventory()->getItemInHand()->getName()));
            return;
        }
        if ($this->enchantment === VanillaEnchantments::SILK_TOUCH()) {
            if ($item->hasEnchantment(EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::FORTUNE))) {
                $player->sendMessage('§bEnchant §7>> §c幸運がついているため、シルクタッチはつけられません');
                return;
            }
        }
        if ($this->enchantment === EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::FORTUNE)) {
            if ($player->getInventory()->getItemInHand()->getNamedTag()->getTag('MiningTools_Expansion_FortuneEnchant') !== null) {
                $player->sendMessage('§bEnchant §7>> §cシルクタッチを幸運に変化されたMiningToolsはエンチャントから不正に強化することはできません');
                return;
            }
            if ($item->hasEnchantment(VanillaEnchantments::SILK_TOUCH())) {
                $player->sendMessage('§bEnchant §7>> §cシルクタッチエンチャントがついているため、幸運はつけられません');
                return;
            }
        }
        EconomyAPI::getInstance()->reduceMoney($player, $price);
        $item->addEnchantment(new EnchantmentInstance($this->enchantment, $this->level));
        $player->getInventory()->setItemInHand($item);
        $player->sendMessage("§bEnchant §7>> §a{$this->enchantName}を{$this->level}レベルで付与しました");
    }

}