<?php

namespace deceitya\miningtools\diamond;

use deceitya\miningtools\Main;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\form\Form;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;

class DiamondToolForm implements Form {

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        $config = Main::getInstance()->config;
        $item = null;
        $set = null;
        switch ($data[0]) {
            case 0:
                $item = ItemFactory::getInstance()->get(ItemIds::DIAMOND_PICKAXE, 0, 1);
                $set = $config['diamond_pickaxe'];
                break;
            case 1:
                $item = ItemFactory::getInstance()->get(ItemIds::DIAMOND_SHOVEL, 0, 1);
                $set = $config['diamond_shovel'];
                break;
            case 2:
                $item = ItemFactory::getInstance()->get(ItemIds::DIAMOND_AXE, 0, 1);
                $set = $config['diamond_axe'];
                break;
            default:
                return;
        }
        $item->setCustomName($set['name']);
        $item->setLore([$set['description']]);
        $nbt = $item->getNamedTag();
        $nbt->setInt('4mining', $data[1]);
        $item->setNamedTag($nbt);
        foreach ($set['enchant'] as $enchant) {
            $item->addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId($enchant[0]), $enchant[1]));
        }
        $player->sendForm(new DiamondConfirmForm($item, 150000));
    }

    public function jsonSerialize() {
        return [
            'type' => 'custom_form',
            'title' => 'DiamondMiningTools',
            'content' => [
                [
                    'type' => 'dropdown',
                    'text' => '§7このツールは修繕することが出来ません
修繕ができるのは最上位のNetheriteMiningToolsのみになります

エンチャント内容
> シルクタッチ Lv.1
> 衝撃 Lv.1§f
> 耐久 Lv.5§f

価格は全て15万円となっています
購入したいツールを選択してください',
                    'options' => [
                        'ツルハシ',
                        'シャベル',
                        'アックス'
                    ],
                    'default' => 0
                ],
                [
                    'type' => 'dropdown',
                    'text' => 'ランクを指定して下さい
横に書いてあるのは採掘範囲になります',
                    'options' => [
                        'Rank1/3×3×1',
                        'Rank2/3×3×2',
                        'Rank3/3×3×3'
                    ],
                    'default' => 0
                ]
            ]
        ];
    }
}
