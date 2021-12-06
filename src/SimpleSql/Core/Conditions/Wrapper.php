<?php

namespace Tqxxkj\SimpleSql\Core\Conditions;

abstract class Wrapper
{
    public function getSqlSet(): string
    {
        return '';
    }
}
