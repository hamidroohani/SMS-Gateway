<?php


namespace App\Components\Filters;


use Jenssegers\Mongodb\Eloquent\Builder;

class FilterBuilder
{

    protected Builder $query;
    protected array $filters;
    protected string $namespace;

    public function __construct($query, $filters, $namespace)
    {
	    $this->query = $query;
	    $this->filters = $filters;
	    $this->namespace = $namespace;
    }

    public function apply(): Builder
    {
        foreach ($this->filters as $name => $value) {

            $normalizedName = ucfirst($name);
            $class = $this->namespace . "\\{$normalizedName}";

            if (!class_exists($class)) {
                continue;
            }

            if (isset($value) && ((!is_array($value) && trim($value) !== '')) || is_array($value)) {
                (new $class($this->query))->handle($value);
            }
        }
        return $this->query;
    }
}
