<?php

namespace deceitya\delenchant;

use onebone\economyapi\EconomyAPI;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\form\Form;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\player\Player;

class ConfirmForm implements Form {

    private EnchantmentInstance $enchant;
    private ?int $level;

    public function __construct(EnchantmentInstance $enchant, ?int $level = null) {
        $this->enchant = $enchant;
        $this->level = $level;
    }

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        if ($data) {
            $inv = $player->getInventory();
            $item = $inv->getItemInHand();
            if ($this->level !== null) {
                $cost = $this->level * 1500;
                if ($cost <= EconomyAPI::getInstance()->myMoney($player)) {
                    if ($this->enchant->getLevel() - $this->level < 1) {
                        $player->sendMessage('§bEditEnchant §7>> §cエンチャントレベルが0になってしまうため実行出来ませんでした');
                        return;
                    }
                    $inv->removeItem($item);
                    $item->removeEnchantment($this->enchant->getType(), $this->level);
                    $item->addEnchantment(new EnchantmentInstance($this->enchant->getType(), $this->enchant->getLevel() - $this->level));
                    $inv->addItem($item);
                    EconomyAPI::getInstance()->reduceMoney($player, $cost);
                    $player->sendMessage("§bEditEnchant §7>> §aエンチャントを削減しました");
                } else {
                    $player->sendMessage('§bEditEnchant §7>> §cお金が足りないため実行出来ません');
                }
            } else {
                $cost = $item->getEnchantment($this->enchant->getType())->getLevel() * 3000;
                if ($cost <= EconomyAPI::getInstance()->myMoney($player)) {
                    $inv->removeItem($item);
                    $item->removeEnchantment($this->enchant->getType());
                    if (empty($item->getEnchantments())) {
                        $item->removeEnchantments();
                    }
                    $inv->addItem($item);
                    EconomyAPI::getInstance()->reduceMoney($player, $cost);
                    $player->sendMessage("§bEditEnchant §7>> §aエンチャントを削除しました");
                } else {
                    $player->sendMessage('§bEditEnchant §7>> §cお金が足りないため実行出来ません');
                }
            }
        }
    }

    public function jsonSerialize() {
        $name = $this->enchant->getType()->getName();
        $mode = $this->level === null ? "削除" : "削減";
        $level = $this->level ?? $this->enchant->getLevel();
        return [
            'type' => 'modal',
            'title' => 'Edit Enchant',
            'content' => "本当に{$name}(Lv{$level})を{$mode}しますか？\n\n削除は1エンチャントで3000円\n削減は1レベル1500円となります",
            'button1' => 'はい',
            'button2' => 'いいえ'
        ];
    }
}
