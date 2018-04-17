<?php
namespace backend\themes\hyii2\widgets\sidebar;

/**
 * Created by PhpStorm.
 * User: hzhuangxianan
 * Date: 2016/11/22
 * Time: 16:13
 */
use Yii;
use backend\widgets\sidebar;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class TopMenu extends SidebarWidget
{
    public function run()
    {
        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->getRoute();
        }
        if ($this->params === null) {
            $this->params = Yii::$app->request->getQueryParams();
        }
        $items = $this->normalizeItems($this->items, $hasActiveChild);
        if (!empty($items)) {
            $html = "\n";
            foreach ($items as $k=>$item){
                //获取高亮样式
                $active = $item['active']?$this->activeCssClass:'';
                $op['class'] = $active;
                $html .= Html::tag('li',Html::tag('a',$item['label'],['data-toggle'=>'tab','data-target'=>'#tab-'.$k]),$op)."\n";
            }
            echo Html::tag('ul', $html, ['class'=>'top-menu']);
        }
    }
}