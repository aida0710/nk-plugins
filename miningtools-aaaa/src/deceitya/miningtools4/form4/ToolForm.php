<?php

namespace deceitya\miningtools4\form4;

use deceitya\miningtools4\Main;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\form\Form;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;

class ToolForm implements Form {

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        $config = Main::getInstance()->config;
        $item = null;
        $set = null;
        switch ($data[0]) {
            case 0:
                $item = ItemFactory::getInstance()->get(745, 0, 1);
                $set = $config['pickaxe'];
                break;
            default:
                return;
        }
        $item->setCustomName($set['name']);
        $item->setLore([$set['description']]);
        $nbt = $item->getNamedTag();
        $nbt->setInt('5mining', $data[1]);
        $item->setNamedTag($nbt);
        foreach ($set['enchant'] as $enchant) {
            $item->addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId($enchant[0]), $enchant[1]));
        }
        $player->sendForm(new ConfirmForm($item, 0));
    }

    public function jsonSerialize() {
        return [
            'type' => 'custom_form',
            'title' => 'DebugMiningTools',
            'content' => [
                [
                    'type' => 'dropdown',
                    'text' => 'デバッグ用ツールです',
                    'options' => [
                        'ツルハシ'
                    ],
                    'default' => 0
                ],
                [
                    'type' => 'dropdown',
                    'text' => '範囲',
                    'options' => [
                        '1',
                        '2',
                        '3',
                        '4',
                        '5',
                        '6',
                        '7',
                        '8',
                        '9',
                        '10',
                        '11',
                        '12',
                        '13',
                        '14',
                        '15',
                        '16',
                        '17',
                        '18',
                        '19',
                        '20',
                        '21',
                        '22',
                        '23',
                        '24',
                        '25',
                        '26',
                        '27',
                        '28',
                        '29',
                        '30',
                        '31',
                        '32',
                        '33',
                        '34',
                        '35',
                    ],
                    'default' => 7
                ]
            ]
        ];
    }
}
