<?php

namespace lazyperson710\sff\form;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use bbo51dog\bboform\form\SimpleForm;
use lazyperson710\sff\form\element\CommandDispatchButton;
use lazyperson710\sff\form\element\SendFormButton;

class InformationForm extends SimpleForm {

    public function __construct() {
        $this->setTitle("InformationForm");
        $this->setText("見たいコンテンツを選択してください");
        $this->addElements(
            new CommandDispatchButton("お知らせ", "announce", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/invite_base.png")),
            new SendFormButton(new CommandListForm(), "コマンドリスト\nコマンドリストを表示する", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/mute_off.png")),
            new SendFormButton(new FunctionServerListForm(), "機能一覧", new ButtonImage(ButtonImage::TYPE_PATH, "textures/items/book_portfolio.png")),
            new SendFormButton(new SpecificationListForm(), "仕様一覧", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/debug_glyph_color.png")),
            new SendFormButton(new TosForm(), "利用規約\nweb版:www.nkserver.net/tos", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/Feedback.png")),
            new Button("閉じる", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/redX1.png")),
        );
    }
}