<?php

namespace deceitya\miningtools\netherite;

use deceitya\miningtools\Main;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\form\Form;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;

class NetheriteToolForm implements Form {

    public const NETHERITE_SHOVEL = 744;
    public const NETHERITE_PICKAXE = 745;
    public const NETHERITE_AXE = 746;

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        $config = Main::getInstance()->config;
        $item = null;
        $set = null;
        switch ($data[0]) {
            case 0:
                $item = ItemFactory::getInstance()->get(self::NETHERITE_PICKAXE, 0, 1);
                $set = $config['netherite_pickaxe'];
                break;
            case 1:
                $item = ItemFactory::getInstance()->get(self::NETHERITE_SHOVEL, 0, 1);
                $set = $config['netherite_shovel'];
                break;
            case 2:
                $item = ItemFactory::getInstance()->get(self::NETHERITE_AXE, 0, 1);
                $set = $config['netherite_axe'];
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
        $player->sendForm(new NetheriteConfirmForm($item, 1500000));
    }

    public function jsonSerialize() {
        return [
            'type' => 'custom_form',
            'title' => 'NetheriteMiningTools',
            'content' => [
                [
                    'type' => 'dropdown',
                    'text' => '§7このツールは修繕が可能です

エンチャント内容
> シルクタッチ Lv.1
> 耐久 Lv.10§f

価格は全て150万円となっています
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
