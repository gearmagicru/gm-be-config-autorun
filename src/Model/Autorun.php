<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Config\Autorun\Model;

use Gm;
use Gm\Db\ActiveRecord;

/**
 * Модель данных автозапуска модулей и их расширений.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Extension\Config\Autorun\Model
 * @since 1.0
 */
class Autorun extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function primaryKey(): string
    {
        return 'id';
    }

    /**
     * {@inheritdoc}
     */
    public function tableName(): string
    {
        return '{{panel_autorun}}';
    }

    /**
     * {@inheritdoc}
     */
    public function maskedAttributes(): array
    {
        return [
            'id'       => 'id',
            'roleId'   => 'role_id',
            'index'    => 'index',
            'priority' => 'priority',
            'enabled'  => 'enabled',
            'route'    => 'route'
        ];
    }

    /**
     * Возвращает доступные маршруты (routes) для запуска модулей и их расширений.
     * 
     * @param bool $addQuotes Экранировать результирующие маршруты одинарными 
     *     кавычками (по умолчанию `true`).
     * @param bool $accessible Если `true`, возвратит все доступные маршруты для 
     *     текущей роли пользователя (по умолчанию `true`).
     * 
     * @return array
     */
    public function getRoutes(bool $addQuotes = true, bool $accessible = true): array
    {
        /** @var \Gm\Db\Adapter\Adapter $db */
        $db = $this->getDb();
        /** @var \Gm\Db\Sql\Select $select */
        $select = $db->select('{{panel_autorun}}');
        $select
            ->columns(['*'])
            ->where(['enabled' => 1])
            ->order(['index' => 'ASC']);
        // если доступные маршруты для текущей роли пользователя
        if ($accessible) {
            $roleId = Gm::userIdentity()->getRoles()->ids(false);
            if (empty($roleId)) {
                return [];
            }
            $select->where(['role_id' => $roleId]);
        }
        /** @var \Gm\Db\Adapter\Driver\AbstractCommand $command */
        $command = $db->createCommand($select);
        $rows = $command->queryAll();
        if ($rows) {
            $routes = [];
            foreach ($rows as $row) {
                $route = $row['route'];
                if ($addQuotes) {
                    $route = "'" . $route . "'";
                }
                $routes[$route] = true;
            }
            return array_keys($routes);
        }
        return [];
    }
}
