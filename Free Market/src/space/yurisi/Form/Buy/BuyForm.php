<?php

declare(strict_types=1);
namespace space\yurisi\Form\Buy;

use pocketmine\form\Form;
use pocketmine\player\Player;
use space\yurisi\Config\YamlConfig;
use space\yurisi\Form\MainForm;

class BuyForm implements Form {

    public function handleResponse(Player $player, $data): void {
        if (!is_numeric($data)) return;
        switch ($data) {
            case 0:
                $cls = new YamlConfig();
                if ($cls->getAllMarket() == null) {
                    $player->sendMessage("§bMarket §7>> §c出品されているアイテムが存在しません");
                    return;
                }
                $buttons = [];
                foreach ($cls->getAllMarket() as $id) {
                    $buttons[] = $cls->getMarketData($id);
                }
                $buttons_reverse = array_reverse($buttons);
                $player->sendForm(new ResultSearchIDForm($buttons_reverse, "1ページ"));
                return;
            case 1:
                $player->sendForm(new SearchIDForm());
                return;
            case 2:
                $player->sendForm(new SearchMarketIDForm());
                return;
        }
        $player->sendForm(new MainForm());
    }

    public function jsonSerialize() {
        $buttons[] = ['text' => "最新の出品アイテム"];
        $buttons[] = ['text' => "アイテムidから検索"];
        $buttons[] = ['text' => "Market IDから検索\n非公開を指定した場合こちらから"];
        $buttons[] = ['text' => "戻る"];
        return [
            "type" => 'form',
            "title" => 'Free Market',
            "content" => "選択してください",
            "buttons" => $buttons,
        ];
    }
}
