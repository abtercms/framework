<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Filter;

class RegexpFilter extends Filter
{
    public const HELP_CONTENT = 'framework:helpRegexp';

    protected const QUERY_TEMPLATE = '%s REGEXP ?';

    /**
     * @param array $params
     *
     * @return $this
     */
    public function setParams(array $params): IFilter
    {
        parent::setParams($params);

        if ($this->value) {
            $this->queryParams = [$this->value];
        }

        return $this;
    }
}
