<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-6-27
 * Time: 上午7:16
 */

namespace App\Demo\Controllers;

use App\Demo\Model\Employees;
use Framework\Controller;
use Framework\Exceptions\CoreHttpException;

class Index extends Controller
{

    /**
     * @return string
     * @throws CoreHttpException
     */
    public function hello()
    {
        return sprintf('Action is "%s",  Route Strategy is "%s"', get_class(), config('route.strategy'));
    }

    /**
     * @return string
     */
    public function add()
    {
        $data = [
            'birth_date' => date('Y-m-d', time()),
            'first_name' => 'Yi',
            'last_name' => 'Yi',
            'gender' => 'M',
            'hire_date' => date('Y-m-d', time())
        ];
        return json_encode(Employees::save($data));
    }

    /**
     * @return string
     */
    public function show()
    {
        return $this->fetch('index/show', ['users' => Employees::where([])->get()]);
    }

    /**
     * @return string
     * @throws CoreHttpException
     */
    public function test()
    {
        request()->check('username', 'require');
        request()->check('password', 'length', 12);
        request()->check('code', 'number');
        return json_encode([
            'username' => request()->get('username', 'default value')
        ]);
    }

    public function view()
    {
        return $this->fetch('index/view', ['body' => 'Test body information', 'users' => [1, 2]]);
    }
}
