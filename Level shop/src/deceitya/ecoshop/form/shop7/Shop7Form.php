<?php

namespace deceitya\ecoshop\form\shop7;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ecoshop\database\LevelShopAPI;
use deceitya\ecoshop\form\element\SellBuyItemFormButton;
use pocketmine\block\VanillaBlocks;
use pocketmine\form\Form;
use pocketmine\player\Player;

class Shop7Form extends SimpleForm {

    public function __construct() {
        $shop = LevelShopAPI::getInstance();
        $contents = [
            new OtherBlocks7(),
            new RedStone(),
        ];
        $this
            ->setTitle("Level Shop")
            ->setText("§7選択してください");
        foreach ($contents as $content) {
            $this->addElements(new SellBuyItemFormButton("{$content->getName()}\n購入:{$shop->getBuy($content->getId())} / 売却:{$shop->getSell($content->getId())}", $content->getId()));
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
        $class = "\\deceitya\\ecoshop\\form\\shop7\\" . $forms[$data];
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
