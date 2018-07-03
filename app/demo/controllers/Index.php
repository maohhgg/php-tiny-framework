<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-6-27
 * Time: 上午7:16
 */
namespace App\Demo\Controllers;

use Framework\Controller;
use Framework\DB\DB;

class Index
{
    /**
     * @return string
     * @throws \Framework\Exceptions\CoreHttpException
     */
    public function hello()
    {
        /* /index.php?c=index&a=hello&m=demo */
        $data = [
            'birth_date' => date('Y-m-d',time()),
            'first_name' => 'Yi',
            'last_name' => 'Yi',
            'gender' => 'M',
            'hire_date' => date('Y-m-d',time())
        ];
        return json_encode(DB::table('employees')->save($data));
    }

    /**
     * @return string
     * @throws \Framework\Exceptions\CoreHttpException
     */
    public function show()
    {
        /* /index.php?c=index&a=show&m=demo */
        $data = DB::table('employees')->where([])->get();
        return json_encode($data);
    }
}
