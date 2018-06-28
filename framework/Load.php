<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-6-26
 * Time: 上午7:54
 */
namespace Framework;

use Framework\Exceptions\CoreHttpException;
use Framework\App;

/**
 * Class Load
 * 注册加载handle
 */
class Load
{
    /**
     * 类名映射
     * @var array
     */
    private static $map;

    /**
     * 命名空间映射
     * @var array
     */
    private static $namespaceMap;


    public static function register(App $app)
    {
        self::$namespaceMap = [
            'Framework' => $app->rootPath
        ];

        // 注册框架加载函数　不使用composer加载机制加载框架　自己实现
        spl_autoload_register(['Framework\Load', 'autoload']);

        // 引入composer自加载文件
        require($app->rootPath . '/vendor/autoload.php');
    }

    /**
     * 自加载函数
     * @param string $class 类名
     * @throws CoreHttpException
     */
    private static function autoload($class)
    {
        $classOrigin = $class;
        $classInfo = explode('\\', $class);
        $className = array_pop($classInfo);

        foreach ($classInfo as &$v) {
            $v = strtolower($v);
        }
        unset($v);

        array_push($classInfo, $className);
        $class = implode('\\', $classInfo);
        $path = self::$namespaceMap['Framework'];
        $classPath = $path .'/'. str_replace('\\', '/', $class) . '.php';

        if (!file_exists($classPath)) {
            return;
            throw new CoreHttpException(404, "$classPath Not Found");
        }

        self::$map[$classOrigin] = $classPath;
        require($classPath);
    }
}