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
        $this->setText("見たいコンテンツを選択してください\nこのFormは/infoで使用可能です");
        $this->addElements(
            new CommandDispatchButton("お知らせ", "announce", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/invite_base.png")),
            new CommandDispatchButton("コマンドリスト\nコマンドリストを表示する", "command", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/mute_off.png")),
            new CommandDispatchButton("機能一覧", "function", new ButtonImage(ButtonImage::TYPE_PATH, "textures/items/book_portfolio.png")),
            new CommandDispatchButton("仕様一覧", "specification", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/debug_glyph_color.png")),
            new SendFormButton(new TosForm(), "利用規約\nweb版:www.nkserver.net/tos.html", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/Feedback.png")),
            new Button("閉じる", new ButtonImage(ButtonImage::TYPE_PATH, "textures/ui/redX1.png")),
        );
    }
}