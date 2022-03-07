<?php

namespace deceitya\debugMiningTools;

use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\form\Form;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use pocketmine\Server;

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
        $nbt->setInt('MiningTools_Debug', $data[1]);
        $item->setNamedTag($nbt);
        foreach ($set['enchant'] as $enchant) {
            $item->addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId($enchant[0]), $enchant[1]));
        }
        if ($player->getInventory()->canAddItem($item)) {
            if (Server::getInstance()->isOp($player->getName())) {
                $player->getInventory()->addItem($item);
                $player->sendMessage('§bMiningTool §7>> §aインベントリにアイテムを付与しました');
            } else {
                $player->sendMessage('§bMiningTool §7>> §c権限は足りません');
            }
        } else {
            $player->sendMessage('§bMiningTool §7>> §cインベントリに空きがありません');
        }
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
                    ],
                    'default' => 2
                ]
            ]
        ];
    }
}
