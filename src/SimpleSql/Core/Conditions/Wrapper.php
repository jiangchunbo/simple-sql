<?php

namespace Tqxxkj\SimpleSql\Core\Conditions;

use Tqxxkj\SimpleSql\Core\Conditions\Segments\MergeSegments;

abstract class Wrapper
{

    protected $entity;

    public function getSqlSet(): string
    {
        return '';
    }

    public abstract function getExpression(): MergeSegments;


    /**
     * 判断 where 子句是否为空
     * @return bool
     */
    public function isEmptyOfWhere(): bool
    {
        return $this->isEmptyOfNormal() && $this->isEmptyOfEntity();
    }

    public function isEmptyOfNormal(): bool
    {
        return $this->getExpression()->getNormal()->isEmpty();
    }

    public function isEmptyOfEntity(): bool
    {
        return !$this->entity;
    }
}
