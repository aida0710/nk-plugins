<?php

namespace lazyperson0710\EffectItems\form\items;


use bbo51dog\bboform\form\SimpleForm;
use lazyperson710\sff\form\element\SendFormButton;

class SelectContentForm  extends SimpleForm {

    public function __construct() {
        $this
            ->setTitle("Item Edit")
            ->setText("編集したい項目を選択してください")
            ->addElements(
                new SendFormButton(new ItemNameChangeForm(), "アイテム名変更"),
                new SendFormButton(new ItemLoreChangeForm(), "アイテム説明変更"),
            );
    }

}