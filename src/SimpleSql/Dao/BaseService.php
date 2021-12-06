<?php

namespace Tqxxkj\SimpleSql\Dao;

use Exception;
use Tqxxkj\SimpleSql\Core\Conditions\AbstractWrapper;
use Tqxxkj\SimpleSql\Core\Conditions\Wrapper;

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
    public function save(&$entity)
    {
        $this->dao->insert($entity);
    }

    /**
     * 批量新增数据
     * @param $entityList
     * @throws Exception
     */
    public function saveBatch(&$entityList)
    {
        foreach ($entityList as &$entity) {
            $this->dao->insert($entity);
        }
    }


    /**
     * 根据 ID 删除一条数据
     * @param $id
     * @return int
     */
    public function removeById($id): int
    {
        return $this->dao->deleteById($id);
    }

    /**
     * @param $idList
     * @return int
     */
    public function remoteByIds($idList): int
    {
        return $this->dao->deleteBatchIds($idList);
    }


    /**
     * 根据 ID 更新实体
     * @param array $entity
     */
    public function updateById($entity)
    {
        $this->dao->updateById($entity);
    }

    public function update(array $entity, Wrapper $updateWrapper)
    {
        return $this->dao->update($entity, $updateWrapper);
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
