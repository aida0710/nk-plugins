<?php

namespace deceitya\miningtools\extensions;

use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\Server;

class SetLoreJudgment {

    public function SetLoreJudgment(Player $player, Item $item): array {
        $lore = "このMiningToolは最上位ツールの為修繕が可能です\n\n現在付与されている特殊効果";
        if ($item->getNamedTag()->getTag('MiningTools_Expansion_Range') !== null) {
            $lore .= "\n\n- 採掘範囲拡張アタッチメント";
            $lore .= match ($item->getNamedTag()->getInt("MiningTools_Expansion_Range")) {
                1 => "\nRank.1 - 範囲5x5x5\n残りアップグレード回数 2 回\n特殊効果 : 始点のブロックを考慮しないで範囲破壊されるように",
                2 => "\nRank.2 - 範囲7x7x7\n残りアップグレード回数 1 回\n特殊効果 : 始点のブロックを考慮しないで範囲破壊されるように",
                3 => "\nRank.3 - 範囲9x9x9\n最終ランク\n特殊効果 : 範囲内のブロックは無条件に破壊されるように",
            };
        }
        if (($item->getNamedTag()->getTag('MiningTools_Expansion_FortuneEnchant') !== null || $item->getNamedTag()->getTag('MiningTools_Expansion_UnbreakingEnchant') !== null)) {
            $lore .= "\n\n- 拡張エンチャント機能";
            if ($item->getNamedTag()->getTag('MiningTools_Expansion_FortuneEnchant') !== null) {
                $lore .= match ($item->getNamedTag()->getInt("MiningTools_Expansion_FortuneEnchant")) {
                    1 => "\nRank.1 - 耐久25\n残りアップグレード回数 2 回",
                    2 => "\nRank.2 - 耐久35\n残りアップグレード回数 1 回",
                    3 => "\nRank.3 - 耐久50 & 修繕1\n最終ランク",
                };
            }
            if ($item->getNamedTag()->getTag('MiningTools_Expansion_UnbreakingEnchant') !== null) {
                $lore .= match ($item->getNamedTag()->getInt("MiningTools_Expansion_UnbreakingEnchant")) {
                    1 => "\nRank.1 - 幸運 1\n残りアップグレード回数 2 回",
                    2 => "\nRank.2 - 幸運 2\n残りアップグレード回数 1 回",
                    3 => "\nRank.3 - 幸運 3\n最終ランク",
                };
            }
        }
        if ($item->getNamedTag()->getTag('MiningTools_Expansion_Effect') !== null) {
            $lore .= "\n\n- 拡張エフェクト効果";
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
            Server::getInstance()->getLogger()->error("[" . $player->getName() . "]" . __DIR__ . "ディレクトリに存在する" . __CLASS__ . "クラスの" . __LINE__ . "行目でエラーが発生しました");
            $lore .= "\n例外エラーが発生したようです。なまけものに報告してください";
        }
        return [0 => $lore];
    }

}