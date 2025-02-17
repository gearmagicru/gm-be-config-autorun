<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * Файл конфигурации Карты SQL-запросов.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    'drop'   => ['{{panel_autorun}}'],
    'create' => [
        '{{panel_autorun}}' => function () {
            return "CREATE TABLE `{{panel_autorun}}` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `role_id` int(11) unsigned DEFAULT NULL,
                `index` int(11) unsigned DEFAULT '1',
                `priority` int(11) unsigned DEFAULT '1',
                `enabled` tinyint(1) unsigned DEFAULT '1',
                `route` varchar(255) DEFAULT NULL,
                `_updated_date` datetime DEFAULT NULL,
                `_updated_user` int(11) unsigned DEFAULT NULL,
                `_created_date` datetime DEFAULT NULL,
                `_created_user` int(11) unsigned DEFAULT NULL,
                `_lock` tinyint(1) unsigned DEFAULT '0',
                PRIMARY KEY (`id`)
            ) ENGINE={engine} 
            DEFAULT CHARSET={charset} COLLATE {collate}";
        }
    ],

    'run' => [
        'install'   => ['drop', 'create'],
        'uninstall' => ['drop']
    ]
];