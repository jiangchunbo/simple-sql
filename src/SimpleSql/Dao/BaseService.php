<?php

namespace Tqxxkj\SimpleSql\Dao;

use Exception;

abstract class BaseService
{
    /**
     * @var BaseDao
     */
    protected $dao;

    protected abstract function tableName(): string;

    /**
     * BaseService constructor.
     * @param $session
     */
    public function __construct($session)
    {
        $this->dao = new BaseDao($session, $this->tableName());
    }

    /**
     * 新增数据
     * @param $entity
     * @throws Exception
     */
    public function save($entity)
    {
        $this->dao->insert($entity);
    }

    /**
     * 批量新增数据
     * @param $entityList
     * @throws Exception
     */
    public function saveBatch($entityList)
    {
        foreach ($entityList as $entity) {
            $this->dao->insert($entity);
        }
    }


    /**
     * @param $id
     * @throws Exception
     */
    public function getById($id)
    {
        $this->dao->selectById($id);
    }

    public function listByIds($idList)
    {
        $this->dao->selectBatchIds($idList);
    }
}
