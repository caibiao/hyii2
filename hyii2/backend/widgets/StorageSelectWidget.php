<?php

namespace abiao\widgets;

use yii\base\Widget;

class StorageSelectWidget extends Widget
{
    public $id;
    public $type;
    public $title;
    public $storageId;
    public $checkList;
    public $onClickJs;
    public $confirmJs;
    public $modalBodyContent;

    public function init()
    {
        parent::init();

        if ($this->id === null) {
            $this->id = 'select_storage';
        }

        if ($this->title === null) {
            $this->title = '分配库位';
        }

        if ($this->storageId === null) {
            $this->storageId = 0;
        }

        if ($this->checkList === null) {
            $this->checkList = '.checkbox:checked';
        }

        if ($this->onClickJs === null) {
            $this->onClickJs = '';
        }

        if ($this->confirmJs === null) {
            $this->confirmJs = '';
        }

        if ($this->modalBodyContent === null) {
            $this->modalBodyContent = '';
        }
    }

    public function run()
    {
        $params = [
            'id' => $this->id,
            'type' => $this->type,
            'title' => $this->title,
            'storageId' => $this->storageId,
            'checkList' => $this->checkList,
            'onClickJs' => $this->onClickJs,
            'confirmJs' => $this->confirmJs,
            'modalBodyContent' => $this->modalBodyContent,
        ];

        if ($this->id == 'select_storage' || $this->id == 'showModalIndex') {
            return $this->render('/widget/storage_select_widget', $params);
        } elseif ($this->id == 'return-order-stocks') {
            return $this->render('/widget/order_storage_select_widget', $params);
        } elseif ($this->id == 'return-in-stocks') {
            return $this->render('/widget/in_storage_select_widget', $params);
        }
    }

}
