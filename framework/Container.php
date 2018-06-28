<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-6-26
 * Time: 上午9:21
 */

namespace Framework;


use Framework\Exceptions\CoreHttpException;


class Container
{
    /**
     * 类映射
     * @var array
     */
    private $classMap = [];

    /**
     * 单例类映射
     * @var array
     */
    public $instanceMap = [];

    /**
     * 注入一个类
     * @param string $alias 类别名
     * @param string $objectName 类名
     * @return mixed
     */
    public function set($alias = '', $objectName = '')
    {
        $this->classMap[$alias] = $objectName;
        if (is_callable($objectName)) {
            return $objectName();
        }
        return new $objectName;
    }


    /**
     * 获取一个类的实例
     * @param string $alias 类名或别名
     * @return mixed
     * @throws CoreHttpException
     */
    public function get($alias = ''): object
    {
        if (array_key_exists($alias, $this->classMap)) {
            $object = $this->classMap[$alias];
            if (is_callable($object)) {
                return $object();
            }
            if (is_object($object)) {
                return $object;
            }
            return new $object;
        }
        throw new CoreHttpException(404, 'Class:' . $alias);
    }


    /**
     * 注入一个单例类
     * @param string $alias 类别名
     * @param string $object 实例或闭包或类名
     * @return mixed
     * @throws CoreHttpException
     */
    public function setSingle($alias = '', $object = '')
    {

        if (empty($alias) && empty($object)) {
            throw new CoreHttpException(400, "{$alias} and {$object} is empty");
        }
        if (empty($alias) && !empty($object)) {
            throw new CoreHttpException(400,"{$alias} is empty");
        }

        if (is_callable($alias)) {
            $instance = $alias();
            $className = get_class($instance);
            $this->instanceMap[$className] = $instance;
            return $instance;
        }

        if (is_callable($object)) {
            if(array_key_exists($alias,$this->instanceMap)){
                return $this->instanceMap[$alias];
            }
            $this->instanceMap[$alias] = $object;
        }

        if (is_object($alias)) {
            $className = get_class($alias);
            if (array_key_exists($className, $this->instanceMap)) {
                return $this->instanceMap[$alias];
            }
            $this->instanceMap[$className] = $alias;
            return $this->instanceMap[$className];
        }

        if (is_object($object)) {
            $this->instanceMap[$alias] = $object;
            return $this->instanceMap[$alias];
        }

        $this->instanceMap[$alias] = new $alias();
        return $this->instanceMap[$alias];
    }


    /**
     * 获取一个单例类
     * @param  string $alias 类名或别名
     * @param string $closure 闭包
     * @return mixed
     * @throws CoreHttpException
     */
    public function getSingle($alias = '', $closure = '')
    {
        if (array_key_exists($alias, $this->instanceMap)) {
            $instance = $this->instanceMap[$alias];
            if (is_callable($instance)) {
                return $this->instanceMap[$alias] = $instance();
            }
            return $instance;
        }

        if (is_callable($closure)) {
            return $this->instanceMap[$alias] = $closure();
        }

        throw new CoreHttpException(404,'Class:' . $alias);
    }
}