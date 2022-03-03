<?php

namespace lazyperson710\sff\form;

class CommandListForm extends FunctionListForm {

    public function __construct() {
        $this
            ->setTitle("CommandList")
            ->setText("作成中のため指摘はしないでください")
            ->addFunction("CommandList/announce", "announce", "アナウンスを見る")
            ->addFunction("CommandList/command", "command", "説明");
    }
}