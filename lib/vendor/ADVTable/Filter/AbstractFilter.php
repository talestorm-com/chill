<?php

namespace ADVTable\Filter;

use \ADVTable\Util\TAccess,
    \ADVTable\Data\IData,
    \ADVTable\Data\PostData;

/**
 * @property string $whereWord return `where` word if contains filters and empty string otherwise
 */
abstract class AbstractFilter {

    use TAccess;

    CONST FILTERS_TOKEN = null;

    /** @var IData */
    protected $idata;

    /** @var Array 
     * ArrayValue matching frontend column names with base column names and typefilters
     */
    protected $filterSelector;

    /** @var Array филтьры, как они пришли */
    protected $filters;

    /** @var SQLFilter\SQLFilter[] */
    protected $builtFilters;

    /** @var Array */
    protected $direct_conditions;

    public function __construct(IData $data = null, array $selector = null) {
        $this->idata = $data ? $data : PostData::F();
        $this->filterSelector = is_array($selector) ? $selector : null;
        $this->init();
    }

    /**
     * 
     * @return \static
     */
    public function init() {
        $this->filters = $this->idata->getPath($this->getFiltersToken(), null);
        $this->filters = is_array($this->filters) ? $this->filters : null;
        $this->builtFilters = null;
        return $this;
    }

    protected function getFiltersToken() {
        return static::FILTERS_TOKEN;
    }

    /**
     * 
     * @param IData $data
     * @param array $r
     * @return \static
     */
    public static function F(IData $data = null, Array $r = null) {
        return new static($data, $r);
    }

    public function addDirectCondition($x) {
        is_array($this->direct_conditions) ? false : $this->direct_conditions = [];
        $this->direct_conditions[] = $x;
        return $this;
    }

    public function buildSQL(array &$params, &$c) {
        if (!$this->builtFilters) {
            $this->buildFilters();
        }
        $result = [];
        $c++;
        foreach ($this->builtFilters as $filter) {
            $filter->getSQL($result, $params, $c);
        }
        $c++;
        if (is_array($this->direct_conditions) && count($this->direct_conditions)) {
            foreach ($this->direct_conditions as $condition) {
                $result[] = $condition;
            }
        }
        return implode(" AND ", $result);
    }

    protected function buildFilters() {
        $this->builtFilters = [];
        foreach ((is_array($this->filters) ? $this->filters : []) as $filterKey => $filterMatch) {
            $filter = SQLFilter\SQLFilter::FACTORY($filterKey, $filterMatch, $this->filterSelector);
            $filter && $filter->valid ? $this->builtFilters[] = $filter : false;
        }
        return $this;
    }

    protected function __get__whereWord() {
        if (!$this->builtFilters) {
            $this->buildFilters();
        }
        return count($this->builtFilters) || (is_array($this->direct_conditions) && count($this->direct_conditions)) ? " WHERE " : "";
    }

    public function filter_exists($name) {
        return is_array($this->filters) && array_key_exists($name, $this->filters) ? true : false;
    }

    public function filter_value($name, $default = null) {
        return $this->filter_exists($name) ? $this->filters[$name] : $default;
    }

}
