<?php

declare(strict_types=1);

namespace WebsiteAdmin\GraphQL\Scalars;

use Carbon\Carbon;
use Exception;
use GraphQL\Error\Error;
use GraphQL\Language\AST\Node;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Contracts\TypeConvertible;

class Date extends ScalarType implements TypeConvertible
{
    /**
     * @var string
     */
    public $name = 'Date';

    /**
     * @var string
     */
    public $description = 'formata Data e.g. 1900-01-01 00:00:00.000';

    /**
     * Serializes an internal value to include in a response.
     *
     * @param mixed $value
     *
     * @return mixed
     *
     * @throws Error
     */
    public function serialize($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
//        return Carbon::parse($value)->format('d/m/Y H:i:s');
//        return Carbon::createFromFormat('d/m/Y H:i:s');
    }

    /**
     * Parses an externally provided value (query variable) to use as an input.
     *
     * In the case of an invalid value this method must throw an Exception
     *
     * @param mixed $value
     *
     * @return mixed
     *
     * @throws Error
     */
    public function parseValue($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
//        return Carbon::parse($value)->format('d/m/Y H:i:s');
//        return Carbon::createFromFormat('d/m/Y H:i:s');
    }

    /**
     * Parses an externally provided literal value (hardcoded in GraphQL query) to use as an input.
     *
     * In the case of an invalid node or value this method must throw an Exception
     *
     * @param Node $valueNode
     * @param mixed[]|null $variables
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function parseLiteral($valueNode, ?array $variables = null)
    {
        return Carbon::parse($valueNode->value)->format('Y-m-d H:i:s');
//        return Carbon::parse($valueNode->value)->format('Y/m/d H:i:s');
//        return Carbon::createFromFormat('d/m/Y H:i:s');
    }

    public function toType(): Type
    {
        return new static();
    }
}
