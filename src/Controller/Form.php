<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Config\Autorun\Controller;

use Gm\Mvc\Module\BaseModule;
use Gm\Panel\Helper\ExtCombo;
use Gm\Panel\Widget\EditWindow;
use Gm\Panel\Widget\Form as WForm;
use Gm\Panel\Controller\FormController;

/**
 * Контроллер формы автозапуска модуля / расширений.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Extension\Config\Autorun\Controller
 * @since 1.0
 */
class Form extends FormController
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
    public function createWidget(): EditWindow
    {
        /** @var EditWindow $window */
        $window = parent::createWidget();

        // панель формы (Gm.view.form.Panel GmJS)
        $window->form->autoScroll = true;
        $window->form->router->route = $this->module->route('/form');
        $window->form->bodyPadding = 10;
        $window->form->controller = 'gm-be-config-autorun-form';
        $window->form->defaults = [
            'labelAlign' => 'right',
            'labelWidth' => 150
        ];
        $window->form->setStateButtons(WForm::STATE_UPDATE, ['info', 'save', 'delete', 'cancel']);
        $window->form->loadJSONFile('/form', 'items', [
            // выпадающий список модулей
            '@moduleCombobox' => ExtCombo::modules('#Module', 'moduleRoute', 'route', [
                'id'        => 'gm-config-autorun-form__modules',
                'listeners' => [
                    'select' => 'selectModule'
                ]
            ]),
            '@rolesCombobox' => ExtCombo::remote('#User role', 'roleId', [
                'proxy' => [
                    'url' =>  ['user-roles/trigger/combo', 'backend'],
                    'extraParams' => [
                        'combo' => 'role',
                        'noneRow' => 0
                    ]
                ]
            ], [
                'xtype'      => 'g-field-combobox',
                'allowBlank' => false
            ])
        ]);

        // окно компонента (Ext.window.Window Sencha ExtJS)
        $window->width = 500;
        $window->autoHeight = true;
        $window->resizable = false;
        $window
            ->setNamespaceJS('Gm.be.config.autorun')
            ->addRequire('Gm.be.config.autorun.FormController');
        return $window;
    }
}
