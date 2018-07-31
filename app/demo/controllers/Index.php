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

class Index extends Controller
{

    /**
     * @return string
     * @throws \Framework\Exceptions\CoreHttpException
     */
    public function hello()
    {
        print_r(config());
    }

    /**
     * @return string
     * @throws \Framework\Exceptions\CoreHttpException
     */
    public function add(){
        $data = [
            'birth_date' => date('Y-m-d', time()),
            'first_name' => 'Yi',
            'last_name' => 'Yi',
            'gender' => 'M',
            'hire_date' => date('Y-m-d', time())
        ];
        return json_encode(DB::table('employees')->save($data));
    }

    /**
     * @return string
     * @throws \Framework\Exceptions\CoreHttpException
     */
    public function show()
    {
        $data = DB::table('employees')->where([])->get();
        return json_encode($data);
    }

    /**
     * @return string
     * @throws \Framework\Exceptions\CoreHttpException
     */
    public function test()
    {
        request()->check('username', 'require');
        request()->check('password', 'length', 12);
        request()->check('code', 'number');
        return json_encode([
            'username' =>  request()->get('username', 'default value')
        ]);
    }

    public function view(){

    }
}
