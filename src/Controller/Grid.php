<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Config\Autorun\Controller;

use Gm;
use Gm\Mvc\Module\BaseModule;
use Gm\Panel\Widget\TabGrid;
use Gm\Panel\Helper\ExtGrid;
use Gm\Panel\Helper\HtmlGrid;
use Gm\Panel\Controller\GridController;
use Gm\Panel\Helper\HtmlNavigator as HtmlNav;

/**
 *  Контроллер автозапуска расширений.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Extension\Config\Autorun\Controller
 * @since 1.0
 */
class Grid extends GridController
{
    /**
     * {@inheritdoc}
     * 
     * @var BaseModule|\Gm\Backend\Config\Autorun\Extension
     */
    public BaseModule $module;

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this
            ->on(self::EVENT_BEFORE_ACTION, function ($controller, $action) {
                if ($action === 'view') {
                    $this->prepareCache();
                }
            });
    }

    /**
     * Подготовить кэш. Если он не создан, создать и заполнить его.
     * 
     * @return void
     */
    public function prepareCache(): void
    {
        // все расширения
        $extensions = [];
        /** @var \Gm\Session\Container  $storage */
        $storage = $this->module->getStorage();
        /** @var array $modulesInfo конфигурации установленных модулей */
        $modulesInfo = Gm::$app->modules->getRegistry()->getListInfo(true, false);
        if ($modulesInfo) {
            foreach ($modulesInfo as $rowId => $moduleInfo) {
                $extensions[$moduleInfo['route']] = [
                    'type' => 'module',
                    'icon' => $moduleInfo['smallIcon'],
                    'name' => $moduleInfo['name'],
                    'desc' => $moduleInfo['description']
                ];
            }
        }
        $storage->extensions = $extensions;
    }

    /**
     * {@inheritdoc}
     */
    public function createWidget(): TabGrid
    {
        /** @var TabGrid $tab Сетка данных (Gm.view.grid.Grid GmJS) */
        $tab = parent::createWidget();

        // столбцы (Gm.view.grid.Grid.columns GmJS)
        $tab->grid->columns = [
            ExtGrid::columnNumberer(),
            ExtGrid::columnAction(),
            [
                'text'      => ExtGrid::columnInfoIcon($this->t('User role')),
                'dataIndex' => 'roleName',
                'cellTip'   => HtmlGrid::tags([
                    HtmlGrid::header('{roleName}'),
                    HtmlGrid::fieldLabel($this->t('Index'), '{index}'),
                    HtmlGrid::fieldLabel($this->t('Priority'), '{priority}'),
                    HtmlGrid::fieldLabel($this->t('Route'), '{route}'),
                    HtmlGrid::fieldLabel($this->t('Component type'), '{extType}'),
                    HtmlGrid::fieldLabel($this->t('Module / Extension'), '{extName}'),
                    HtmlGrid::fieldLabel($this->t('Added to autorun'), HtmlGrid::tplChecked('enabled')),
                ]),
                'filter'    => ['type' => 'string'],
                'sortable'  => true,
                'width'     => 220
            ],
            [
                'text'      => '#Index',
                'dataIndex' => 'index',
                'tooltip'    => '#Index number',
                'cellTip'   => '{index}',
                'filter'    => ['type' => 'number'],
                'sortable'  => true,
                'width'     => 100
            ],
            [
                'text'      => '#Priority',
                'dataIndex' => 'priority',
                'tooltip'    => '#Priority of the component to run (if the user has multiple roles)',
                'cellTip'   => '{priority}',
                'filter'    => ['type' => 'number'],
                'sortable'  => true,
                'width'     => 100
            ],
            [
                'text'      => '#Route',
                'dataIndex' => 'route',
                'tooltip'    => '#Launched component route',
                'cellTip'   => '{route}',
                'filter'    => ['type' => 'string'],
                'sortable'  => true,
                'width'     => 220
            ],
            [
                'text'      => '#Component type',
                'tooltip'   => '#Component type (module, extension)',
                'dataIndex' => 'extType',
                'sortable'  => false,
                'width'     => 180,
                'hidden'    => true
            ],
            [
                'text'      => '#Module / Extension',
                'dataIndex' => 'extName',
                'cellTip'   => '{extDescription}',
                'sortable'  => false,
                'width'     => 220,
                'hidden'    => true
            ],
            [
                'xtype'     => 'g-gridcolumn-switch',
                'text'      => ExtGrid::columnIcon('g-icon-m_visible', 'svg'),
                'tooltip'   => '#Autorun enabled',
                'selector'  => 'gridpanel',
                'dataIndex' => 'enabled',
                'filter'    => ['type' => 'boolean']
            ]
        ];

        // панель инструментов (Gm.view.grid.Grid.tbar GmJS)
        $tab->grid->tbar = [
            'padding' => 1,
            'items'   => ExtGrid::buttonGroups([
                'edit' => [
                    'items' => [
                        // инструмент "Добавить"
                        'add' => [
                            'iconCls' => 'g-icon-svg gm-config-autorun__icon-add',
                            'tooltip' => '#Adding an extension route for autostart',
                            'caching' => true
                        ],
                        // инструмент "Удалить"
                        'delete' => [
                            'iconCls'     => 'g-icon-svg gm-config-autorun__icon-delete',
                            'msgConfirm'  => '#Are you sure you want to remove selected items from autorun?',
                            'tooltip'     => '#Removing selected items from autorun'
                        ],
                        'cleanup',
                        '-',
                        'edit',
                        'select',
                        '-',
                        'refresh'
                    ]
                ],
                'columns',
                // группа инструментов "Поиск"
                'search' => [
                    'items' => [
                        'help',
                        'search'
                    ]
                ]
            ], [
                'route' => $this->module->route()
            ])
        ];

        // контекстное меню записи (Gm.view.grid.Grid.popupMenu GmJS)
        $tab->grid->popupMenu = [
            'cls'        => 'g-gridcolumn-popupmenu',
            'titleAlign' => 'center',
            'width'      => 150,
            'items'      => [
                [
                    'text'        => '#Edit record',
                    'iconCls'     => 'g-icon-svg g-icon-m_edit g-icon-m_color_default',
                    'handlerArgs' => [
                        'route'   => $this->module->route('/form/view/{id}'),
                        'pattern' => 'grid.popupMenu.activeRecord'
                    ],
                    'handler' => 'loadWidget'
                ]
            ]
        ];

        // 2-й клик по строке сетки
        $tab->grid->rowDblClickConfig = [
            'allow' => true,
            'route' => $this->module->route('/form/view/{id}')
        ];
        // сортировка сетки по умолчанию
        $tab->grid->sorters = [ // сортировка сетки по умолчанию
            ['property' => 'roleName', 'direction' => 'ASC']
         ];
        // количество строк в сетке
        $tab->grid->store->pageSize = 50;
        // поле аудита записи
        $tab->grid->logField = 'roleName';
        // плагины сетки
        $tab->grid->plugins = 'gridfilters';
        // класс CSS применяемый к элементу body сетки
        $tab->grid->bodyCls = 'g-grid_background';

        // панель навигации (Gm.view.navigator.Info GmJS)
        $tab->navigator->info['tpl'] = HtmlNav::tags([
            HtmlNav::header('{route}'),
            HtmlGrid::fieldLabel($this->t('User role'), '{roleName}'),
            HtmlNav::fieldLabel($this->t('Index'), '{index}'),
            HtmlNav::fieldLabel($this->t('Priority'), '{priority}'),
            HtmlNav::fieldLabel($this->t('Component type'), '{extType}'),
            HtmlNav::fieldLabel($this->t('Module / Extension'), '{extName}'),
            HtmlNav::fieldLabel(
                ExtGrid::columnIcon('g-icon-m_visible', 'svg') . ' ' . $this->t('Added to autorun'), 
                HtmlNav::tplChecked('enabled')
            ),
            HtmlNav::widgetButton(
                $this->t('Edit record'),
                ['route' =>  $this->module->route('/form/view/{id}'), 'long' => true],
                ['title' => $this->t('Edit record')]
            )
        ]);

        $tab
            ->addCss('/grid.css')
            ->addRequire('Gm.view.grid.column.Switch');
        return $tab;
    }
}
