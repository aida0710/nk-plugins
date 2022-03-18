<?php

namespace deceitya\miningTools\diamond;

use deceitya\miningTools\Main;
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
        $nbt->setInt('MiningTools_3', 1);
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
                    'text' => "§7このツールは修繕することが出来ません\n修繕ができるのは最上位のNetheriteMiningToolsのみになります\n\nエンチャント内容\n> シルクタッチ Lv.1\n> 衝撃 Lv.1\n> 耐久 Lv.5§f\n\n価格は全て15万円となっています\n購入したいツールを選択してください",
                    'options' => [
                        'ツルハシ',
                        'シャベル',
                        'アックス',
                    ],
                    'default' => 0
                ],
            ]
        ];
    }
}
