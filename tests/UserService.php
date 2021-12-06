<?php


namespace Tqxxkj\SimpleSql\Tests;

use Tqxxkj\SimpleSql\Dao\BaseService;

class UserService extends BaseService
{

    public function insertMore()
    {
        $this->dao->getSqlSession()->getConnection()->getPdo()->beginTransaction();
        $user1 = [
            'username' => '1',
            'password' => '1',
            'password_salt' => '1'
        ];
        $this->dao->insert($user1);
        $user2 = [
            'username' => '1',
            'password' => '1',
            'password_salt' => '1'
        ];
        $this->dao->insert($user2);
        $user3 = [
            'username' => '1',
            'password' => '1',
            'password_salt' => '1'
        ];
        $this->dao->insert($user3);
        $this->dao->getSqlSession()->getConnection()->commit();
    }

    protected function tableName(): string
    {
        return 'users';
    }
}

