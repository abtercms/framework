<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Filter;

class PrefixFilter extends Filter
{
    public const HELP_CONTENT = 'framework:helpPrefix';

    protected const QUERY_TEMPLATE = '%s LIKE ?';

    /**
     * @param array $params
     *
     * @return $this
     */
    public function setParams(array $params): IFilter
    {
        parent::setParams($params);

        if ($this->value) {
            $this->queryParams = [sprintf('%s%%', $this->value)];
        }

        return $this;
    }
}
