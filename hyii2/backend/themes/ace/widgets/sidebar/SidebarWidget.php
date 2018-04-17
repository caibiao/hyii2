<?php
namespace backend\themes\ace\widgets\sidebar;

/**
 * 后台siderbar插件
 */
use Yii;
use yii\widgets\Menu;
use mdm\admin\components\MenuHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

class SidebarWidget extends Menu
{
    public $linkTemplate = '<a href="{url}" class="dropdown-toggle"><i class="{icon}"></i><span class="menu-text">{label}</span>{down}</a>';
    
    public $submenuTemplate = "\n<ul class=\"submenu\">\n{items}\n</ul>\n";
    
    public $options = ['class'=>'nav nav-list'];
    
    public $activateParents = true;
    
    public $labelTemplate = '<a href= >{label}</a>';
    
    public function init()
    {
        if(empty($this->items)){
            
            //默认仪表盘菜单显示
            $this->items = [
                [
                    'label' => '主页',
                    'url' => ['/site/index'],
                    'icon' => 'fa fa-dashboard',
                    'down' => '<b class="arrow icon-angle-down"></b>',
                    'items' => [
                        [
                            'label' => '欢迎页',
                            'url' => ['/site/index'],
                            'icon' => 'fa fa-bookmark-o',
                        ]
                    ]
                ],                     
            ];
            
            //回调函数
            $callback = function ($menu) {
                
                if(isset($menu['data']) && !empty($menu['data'])){
                    $data = json_decode($menu['data']);
                }
                
                return [
                        'label' => $menu['name'],
                        'url' => [$menu['route']],
                        'items' => $menu['children'],
                        'icon' => isset($data->icon)?$data->icon:'',
                        'down' => '<b class="arrow icon-angle-down"></b>',
                        ];
                 };
                 
            //获取有权限的菜单   
            $menu = MenuHelper::getAssignedMenu(\Yii::$app->user->identity->id, null, $callback);
            
            $this->items = array_merge($this->items, $menu);

        }
    }
    
    /**
     * Normalizes the [[items]] property to remove invisible items and activate certain items.
     * @param array $items the items to be normalized.
     * @param boolean $active whether there is an active child menu item.
     * @return array the normalized menu items
     */
    protected function normalizeItems($items, &$active)
    {        
        foreach ($items as $i => $item) {
            if (!isset($item['label'])) {
                $item['label'] = '';
            }
            $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
            $items[$i]['label'] = $encodeLabel ? Html::encode($item['label']) : $item['label'];
            $hasActiveChild = false;
            if (isset($item['items'])) {
                $items[$i]['items'] = $this->normalizeItems($item['items'], $hasActiveChild);
                if (empty($items[$i]['items']) && $this->hideEmptyItems) {
                    unset($items[$i]['items']);
                    if (!isset($item['url'])) {
                        unset($items[$i]);
                        continue;
                    }
                    $items[$i]['down'] = ''; 
                }
            }
            if (!isset($item['active'])) {
                if ($this->activateParents && $hasActiveChild || $this->activateItems && $this->isItemActive($item)) {
                    $active = $items[$i]['active'] = true;
                } else {
                    $items[$i]['active'] = false;
                }
            } elseif ($item['active']) {
                $active = true;
            }
            	
            if (isset($item['visible']) && !$item['visible']) {
                unset($items[$i]);
                continue;
            }
        }

        return array_values($items);
    }
    
    /**
     * Renders the content of a menu item.
     * Note that the container and the sub-menus are not rendered here.
     * @param array $item the menu item to be rendered. Please refer to [[items]] to see what data might be in the item.
     * @return string the rendering result
     */
    protected function renderItem($item)
    {
        if (isset($item['url'])) {
            $template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);
            return strtr($template, [
                '{url}' => Html::encode(Url::to($item['url'])),
                '{label}' => $item['label'],
                '{icon}' => isset($item['icon'])?$item['icon']:'',
                '{down}' => isset($item['down'])?$item['down']:'',
            ]);
        } else {
            $template = ArrayHelper::getValue($item, 'template', $this->labelTemplate);
    
            return strtr($template, [
                '{label}' => $item['label'],
                '{icon}' => isset($item['icon'])?$item['icon']:'',
                '{down}' => isset($item['down'])?$item['down']:'',
            ]);
        }
    }
    
    /**
     * Checks whether a menu item is active.
     * This is done by checking if [[route]] and [[params]] match that specified in the `url` option of the menu item.
     * When the `url` option of a menu item is specified in terms of an array, its first element is treated
     * as the route for the item and the rest of the elements are the associated parameters.
     * Only when its route and parameters match [[route]] and [[params]], respectively, will a menu item
     * be considered active.
     * @param array $item the menu item to be checked
     * @return boolean whether the menu item is active
     */
    protected function isItemActive($item)
    {
        if (isset($item['url']) && is_array($item['url']) && isset($item['url'][0])) {
            $route = Yii::getAlias($item['url'][0]);
            if ($route[0] !== '/' && Yii::$app->controller) {
                $route = Yii::$app->controller->module->getUniqueId() . '/' . $route;
            }
            
            $arrayRoute = explode('/', ltrim($route, '/'));
            // var_dump($arrayRoute, $this->route);
            $arrayThisRoute = explode('/', $this->route);

            //改写了路由的规则，是否高亮判断到controller而非action
            $routeCount = count($arrayRoute);
            if ($routeCount == 2) {
                if ($arrayRoute[0] !== $arrayThisRoute[0]) {
                    return false;
                }
            } elseif ($routeCount == 3) {
                if ($arrayRoute[0] !== $arrayThisRoute[0]) {
                    return false;
                }
                if (isset($arrayRoute[1]) && $arrayRoute[1] !== $arrayThisRoute[1]) {
                    return false;
                }
            } else {
                return false;
            }
            
            unset($item['url']['#']);
            if (count($item['url']) > 1) {
                $params = $item['url'];
                unset($params[0]);
                foreach ($params as $name => $value) {
                    if ($value !== null && (!isset($this->params[$name]) || $this->params[$name] != $value)) {
                        return false;
                    }
                }
            }
    
            return true;
        }
    
        return false;
    }
}