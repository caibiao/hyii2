<?php
/**
 *
 * @link http://www.abiao.com/
 * @copyright Copyright (c) 2015 Anlewo Ltd
 * @license 广东安乐窝网络科技有限公司
 * @author liufujingshou@abiao.com
 * @date 2017-01-04
 */

namespace abiao\widgets;

use yii\base\InvalidConfigException;
use yii\bootstrap\Html;
use yii\widgets\LinkPager as YiiLinkPager;
use Yii;

class LinkPager extends YiiLinkPager
{
    /**
     * {pageButtons} {summary}
     */
    public $template = '<div class="form-inline">{summary}{pageButtons}</div>';

    public $pagination;

    public $summary;
    public $summaryOptions = ['class' => 'summary'];


    /**
     * 初始化
     */
    public function init()
    {
        parent::init();

        if($this->pagination === null){
            throw new InvalidConfigException('The "pagination" property must be set.');
        }
    }

    /**
     * 运行插件
     */
    public function run()
    {
        if ($this->registerLinkTags) {
            $this->registerLinkTags();
        }
        echo $this->renderPageContent();
    }

    /**
     * 替换template函数中的{pageButtons}\{summary}
     * @return mixed
     */
    protected function renderPageContent()
    {
        return preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) {
            $name = $matches[1];

            switch ($name) {
                case 'summary':
                    return $this->renderCustomSummary();
                    break;
                case 'pageButtons':
                    return $this->renderPageButtons();
                    break;
                default:
                    return '';
                    break;
            }
        }, $this->template);
    }

    /**
     * 返回每页中开始位置、结束位置及总数
     * @return string
     */
    protected function renderCustomSummary()
    {
        $totalCount = $this->pagination->totalCount;

        if ($totalCount <= 0) {
            return '';
        }

        if(($this->pagination->getPage()+1) == $this->pagination->getPageCount()){
            $count = $totalCount - $this->pagination->getPage() * $this->pagination->getPageSize();
        }else{
            $count = $this->pagination->getPageSize();
        }


        $tag = 'span';
        $summaryOptions = $this->summaryOptions;

        if ($this->pagination !== false) {

            $begin = $this->pagination->getPage() * $this->pagination->getPageSize() + 1;
            $end = $begin + $count - 1;
            if ($begin > $end) {
                $begin = $end;
            }
            $page = $this->pagination->getPage() + 1;
            $pageCount = $this->pagination->pageCount;

            if (($summaryContent = $this->summary) === null) {
                return Html::tag($tag, Yii::t('yii', '第<b>{begin, number}-{end, number}</b>条，共<b>{totalCount, number}</b>条数据', [
                    'begin' => $begin,
                    'end' => $end,
                    'count' => $count,
                    'totalCount' => $totalCount,
                    'page' => $page,
                    'pageCount' => $pageCount,
                ]), $summaryOptions);
            }

        } else {

            $begin = $page = $pageCount = 1;
            $end = $totalCount = $count;
            if (($summaryContent = $this->summary) === null) {
                return Html::tag($tag, Yii::t('yii', '共<b>{totalCount, number}</b>条数据', [
                    'begin' => $begin,
                    'end' => $end,
                    'count' => $count,
                    'totalCount' => $totalCount,
                    'page' => $page,
                    'pageCount' => $pageCount,
                ]), $summaryOptions);
            }
        }
    }

}