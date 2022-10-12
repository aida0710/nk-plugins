<?php

namespace deceitya\ShopAPI\form\levelShop\shop4;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\database\LevelShopAPI;
use deceitya\ShopAPI\form\element\FirstBackFormButton;
use deceitya\ShopAPI\form\element\SellBuyItemFormButton;
use deceitya\ShopAPI\form\element\ShopItemFormButton;
use deceitya\ShopAPI\form\levelShop\MainLevelShopForm;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\player\Player;

class Shop4Form extends SimpleForm {

    public function __construct() {
        $contents = [
            "Elytra" => 444,
            "その他ブロック" => "OtherBlocks4",
            "武器類" => "Weapon",
        ];
        $this
            ->setTitle("Level Shop")
            ->setText("§7選択してください");
        foreach ($contents as $key => $value) {
            $class = __NAMESPACE__ . "\\" . $value;
            if (is_int($value)) {
                $shop = LevelShopAPI::getInstance();
                $item = match ($value) {
                    444 => "Elytra",
                    default => "Undefined Error",
                };
                $this->addElements(new SellBuyItemFormButton("{$item}\n購入:{$shop->getBuy($value)} / 売却:{$shop->getSell($value)}", $value, 0));
                continue;
            }
            $this->addElements(new ShopItemFormButton($key, $class));
        }
        $this->addElements(new FirstBackFormButton("ホームに戻る"));
    }

    public function handleClosed(Player $player): void {
        SoundPacket::Send($player, 'mob.shulker.close');
        SendForm::Send($player, (new MainLevelShopForm($player)));
    }
}
