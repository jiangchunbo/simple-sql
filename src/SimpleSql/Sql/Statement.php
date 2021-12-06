<?php


namespace Tqxxkj\SimpleSql\Sql;


interface Statement
{
    public function getGeneratedKeys(): array;
}