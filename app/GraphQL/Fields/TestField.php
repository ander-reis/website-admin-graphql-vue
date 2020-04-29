<?php

declare(strict_types=1);

namespace WebsiteAdmin\GraphQL\Fields;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Field;

class TestField extends Field
{
    protected $attributes = [
        'name' => 'TestField',
    ];

    public function type(): Type
    {
        return Type::string();
    }

    public function resolve($root, $args): string
    {
        return 'test';
    }
}
