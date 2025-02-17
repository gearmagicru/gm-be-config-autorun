<?php
/**
 * Расширение модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Config\Autorun;

/**
 * Расширение "Автозапуск".
 * 
 * Настройка автозапуска модулей и их расширений в Панели управления.
 * 
 * Расширение принадлежит модулю "Конфигурация".
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Extension\Config\Autorun
 * @since 1.0
 */
class Extension extends \Gm\Panel\Extension\Extension
{
    /**
     * {@inheritdoc}
     */
    public string $id = 'gm.be.config.autorun';

    /**
     * {@inheritdoc}
     */
    public string $defaultController = 'grid';
}