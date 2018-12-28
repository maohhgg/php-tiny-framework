<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-8-2
 * Time: 上午6:50
 */

namespace Framework\Orm;

use Framework\App;
use Framework\Exceptions\CoreHttpException;

/**
 * Class Model
 * @package Framework\Orm
 */
class Model
{
    /**
     * @var DB
     */
    private static $instance;

    public $tableName = '';

    /**
     * @param $method
     * @param $parameters
     * @return string
     * @throws CoreHttpException
     */
    public static function __callStatic($method, $parameters)
    {
        self::$instance = APP::$container->getSingle('DB', function () {
            return new DB();
        });
        if (empty(self::$instance->tableName)) {
            $callClassName = get_called_class();
            (new self())->getTableName($callClassName);
        }
        return self::$instance->$method(...$parameters);
    }

    /**
     * 获取表名
     * @throws CoreHttpException
     */
    private function getTableName($callClassName)
    {
        $prefix = config('database.prefix');

        $callClassName = explode('\\', $callClassName);
        $callClassName = array_pop($callClassName);
        if (!empty($this::$instance->tableName)) {
            if (empty($prefix)) {
                return;
            }
            $this::$instance->tableName = $prefix . '_' . $this::$instance->tableName;
            return;
        }

        preg_match_all('/([A-Z][a-z]*)/', $callClassName, $match);

        if (!isset($match[1][0]) || empty($match[1][0])) {
            throw new CoreHttpException('401', 'model name invalid');
        }
        $match = $match[1];
        $count = count($match);
        if ($count === 1) {
            $this::$instance->tableName = strtolower($match[0]);
            if (empty($prefix)) {
                return;
            }
            $this::$instance->tableName = $prefix . '_' . $this::$instance->tableName;
            return;
        }
        $last = strtolower(array_pop($match));
        foreach ($match as $v) {
            $this::$instance->tableName .= strtolower($v) . '_';
        }
        $this::$instance->tableName .= $last;
        if (empty($prefix)) {
            return;
        }
        $this::$instance->tableName = $prefix . '_' . $this::$instance->tableName;
    }

}