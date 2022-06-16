<?php

namespace deceitya\miningtools\extensions\range;

use pocketmine\item\Item;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\Server;

class SetLoreJudgment {

    public function SetLoreJudgment(Player $player, Item $item): array {
        $lore = "このMiningToolは最上位ツールの為修繕が可能です\n\n現在付与されている特殊効果";
        if ($item->getNamedTag()->getTag('MiningTools_Expansion_Range') !== null) {
            switch ($item->getNamedTag()->getInt("MiningTools_Expansion")) {
                case 1:
                    $lore .= "\n範囲拡張 Rank.1[5x5x5]";
                    break;
                case 2:
                    $lore .= "\n範囲拡張 Rank.2[7x7x7]";
                    break;
                case 3:
                    $lore .= "\n範囲拡張 Rank.3[9x9x9]";
                    break;
            }
        }
        if ($item->getNamedTag()->getTag('MiningTools_Expansion_Enchant') !== null) {
            $lore .= "\n拡張エンチャント";
            foreach ($player->getInventory()->getItemInHand()->getEnchantments() as $enchant) {
                $enchantName = $enchant->getType()->getName();
                if ($enchantName instanceof Translatable) {
                    $enchantName = Server::getInstance()->getLanguage()->translate($enchantName);
                }
                $lore .= "{$enchantName}(Lv{$enchant->getLevel()})";
            }
        }
        if ($item->getNamedTag()->getTag('MiningTools_Expansion_Effect') !== null) {
            $lore .= "\n拡張エフェクト効果";
            if ($item->getNamedTag()->getTag('MTE_Effect_FireResistance') !== null) {
                $lore .= "\n火炎耐性 lv.1";
            }
            if ($item->getNamedTag()->getTag('MTE_Effect_WaterBreathing') !== null) {
                $lore .= "\n水中呼吸 lv.8";
            }
            if ($item->getNamedTag()->getTag('MTE_Effect_NightVision') !== null) {
                $lore .= "\n暗視 lv.1";
            }
            if ($item->getNamedTag()->getTag('MTE_Effect_Haste') !== null) {
                $lore .= "\n採掘速度上昇 lv.3";
            }
            if ($item->getNamedTag()->getTag('MTE_Effect_Saturation') !== null) {
                $lore .= "\n満腹度回復 lv.1";
            }
            if ($item->getNamedTag()->getTag('MTE_Effect_HealthBoost') !== null) {
                $lore .= "\n体力増強 lv.8";//todo 要検討
            }
            if ($item->getNamedTag()->getTag('MTE_Effect_Regeneration') !== null) {
                $lore .= "\n再生能力 lv.15";
            }
            if ($item->getNamedTag()->getTag('MTE_Effect_Resistance') !== null) {
                $lore .= "\n耐性 lv.5";
            }
        }
        if ($lore === "このMiningToolは最上位ツールの為修繕が可能です\n\n現在付与されている特殊効果") {
            $lore .= "\n例外エラーが発生したようです。なまけものに報告してください";
        }
        return [0 => $lore];
    }

}