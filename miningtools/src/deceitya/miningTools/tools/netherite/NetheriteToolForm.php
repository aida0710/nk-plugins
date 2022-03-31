<?php

namespace deceitya\miningTools\tools\netherite;

use deceitya\miningTools\Main;
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
        $nbt->setInt('MiningTools_3', 2);
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
                    'text' => "§7このツールは修繕が可能です\n\nエンチャント内容\n> シルクタッチ Lv.1\n> 耐久 Lv.10§f\n\n価格は全て150万円となっています\n購入したいツールを選択してください",
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
