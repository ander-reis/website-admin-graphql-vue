<?php

declare(strict_types=1);

namespace WebsiteAdmin\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class AccountType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Account',
        'description' => 'A type'
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'O id do usuário no banco'
            ],
            'description' => [
                'type' => Type::string(),
                'description' => 'A descrição de account'
            ],
            'created_at' => [
                'type' => Type::string(),
                'description' => 'created_at'
            ]
        ];
    }
}
