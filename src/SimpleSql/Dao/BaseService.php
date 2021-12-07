<?php

namespace Tqxxkj\SimpleSql\Dao;

use Exception;
use Tqxxkj\SimpleSql\Core\Conditions\AbstractWrapper;
use Tqxxkj\SimpleSql\Core\Conditions\Query\QueryWrapper;
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
     * @param QueryWrapper|null $queryWrapper
     * @return array
     * @throws Exception
     */
    public function getOne(QueryWrapper $queryWrapper = null)
    {
        return $this->dao->selectOne($queryWrapper);
    }

    /**
     * @param $id
     * @return array
     * @throws Exception
     */
    public function getById($id)
    {
        return $this->dao->selectById($id);
    }

    /**
     * @param $idList
     * @return array
     * @throws Exception
     */
    public function listByIds($idList)
    {
        return $this->dao->selectBatchIds($idList);
    }

    /**
     * @param QueryWrapper $queryWrapper
     * @return int
     * @throws Exception
     */
    public function count(QueryWrapper $queryWrapper = null)
    {
        return $this->dao->selectCount($queryWrapper);
    }


    public function list(QueryWrapper $queryWrapper = null)
    {
        return $this->dao->selectList($queryWrapper);
    }
}
