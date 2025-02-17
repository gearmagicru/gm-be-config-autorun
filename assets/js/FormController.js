/*!
 * Контроллер формы.
 * Расширение "Автозапуск".
 * Модуль "Конфигурация".
 * Copyright 2015 Вeб-студия GearMagic. Anton Tivonenko <anton.tivonenko@gmail.com>
 * https://gearmagic.ru/license/
 */

Ext.define('Gm.be.config.autorun.FormController', {
    extend: 'Gm.view.form.PanelController',
    alias: 'controller.gm-be-config-autorun-form',

    /**
     * Правка маршрута модуля / расширения.
     * @param {Ext.form.field.TextField} me
     * @param {Ext.event.Event} e
     * @param {Object} eOpts
     */
    keydownRoute: function (me, e, eOpts) {
        this.getViewCmp('modules').setValue(me.value);
    },

    /**
     * Выбор модуля из списка.
     * @param {Ext.form.field.ComboBox} combo
     * @param {Ext.data.Model|Ext.data.Model[]} record
     * @param {Object} eOpts
     */
     selectModule: function (combo, record, eOpts) {
         this.getViewCmp('route').setValue(record.get('route'));
    }
});
