<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-7-2
 * Time: 下午2:51
 */

namespace Framework\Orm\Builder;


use Framework\Orm\DB;

interface Builder
{
    public function first(DB $db);
    public function get(DB $db);
    public function save(DB $db);
    public function update(DB $db);
    public function delete(DB $db);
    public function query(DB $db);
}