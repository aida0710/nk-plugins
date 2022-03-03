<?php

namespace bbo51dog\announce\form;

use bbo51dog\announce\AnnounceType;
use bbo51dog\announce\form\element\OpenAnnounceButton;
use bbo51dog\announce\model\Announce;
use bbo51dog\announce\service\AnnounceService;
use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\form\SimpleForm;

class AnnounceForm extends SimpleForm {

    public function __construct(?int $announceId) {
        $this->setTitle("Announce");
        if ($announceId === null) {
            $this
                ->setText("表示するアナウンスがありません")
                ->addElement(new Button("閉じる"));
        } else {
            $announce = AnnounceService::getAnnounce($announceId);
            if ($announce instanceof Announce) {
                $date = date("Y年m月d日H時i分", $announce->getTimestamp());
                $type = AnnounceType::TYPE_INT_TO_STR[$announce->getType()];
                $latest = AnnounceService::getLatestAnnounceId();
                $oldest = AnnounceService::getOldestAnnounceId();
                for ($back = $announceId - 1; $back >= $oldest; $back--) {
                    if (AnnounceService::exsitsAnnounce($back)) {
                        $this->addElement(new OpenAnnounceButton("< Back", $back));
                        break;
                    }
                }
                for ($next = $announceId + 1; $next <= $latest; $next++) {
                    if (AnnounceService::exsitsAnnounce($next)) {
                        $this->addElement(new OpenAnnounceButton("Next >", $next));
                        break;
                    }
                }
                $this
                    ->addElements(
                        new OpenAnnounceButton("最新のアナウンス", $latest),
                        new Button("閉じる"),
                    )
                    ->setText("Update Time: $date\nType: $type\n\nContent: {$announce->getContent()}\n\nWebサイト: nkserver.net\nDiscordグループ: nkserver.net/dで参加可能です");
            } else {
                $this
                    ->setText("表示するアナウンスがありません")
                    ->addElement(new Button("閉じる"));
            }
        }
    }
}