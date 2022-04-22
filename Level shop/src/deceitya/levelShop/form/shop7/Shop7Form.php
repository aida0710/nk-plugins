<?php

namespace deceitya\levelShop\form\shop7;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\levelShop\database\LevelShopAPI;
use deceitya\levelShop\form\element\SellBuyItemFormButton;
use pocketmine\block\VanillaBlocks;
use pocketmine\form\Form;
use pocketmine\player\Player;

class Shop7Form extends SimpleForm {

    public function __construct() {
        $shop = LevelShopAPI::getInstance();
        $contents = [
            [
                new OtherBlocks7(),
                "OtherBlocks7",//連想配列でよくね！！！！！！！！！！！！！！！
            ],

            new RedStone(),
        ];
        $this
            ->setTitle("Level Shop")
            ->setText("§7選択してください");
        foreach ($contents as $content) {
            $this->addElements(new SellBuyItemFormButton("{$content->getName()}\n購入:{$shop->getBuy($content->getId() ,$content->getMeta())} / 売却:{$shop->getSell($content->getId() ,$content->getMeta())}", $content->getId(), $content->getMeta()));
        }
    }
    /*public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        $forms = [
            'RedStone',
            'OtherBlocks5',
        ];
        $class = "\\deceitya\\levelShop\\form\\shop7\\" . $forms[$data];
        $player->sendForm(new $class());
    }

    public function jsonSerialize() {
        $shop = LevelShopAPI::getInstance();
        return [
            'type' => 'form',
            'title' => 'LevelShop',
            'content' => "§7選択してください",
            'buttons' => [
                ['text' => 'RedStone'],
                ['text' => 'OtherBlocks5'],
            ]
        ];
    }*/
}
