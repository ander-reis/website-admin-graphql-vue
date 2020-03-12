<?php

declare(strict_types=1);

namespace WebsiteAdmin\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use WebsiteAdmin\Models\NoticiasCategoria;

class NoticiaCategoriaType extends GraphQLType
{
    protected $attributes = [
        'name' => 'NoticiaCategoria',
        'description' => 'type noticia categoria',
        'model' => NoticiasCategoria::class
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'id categoria',
            ],
            'ds_categoria' => [
                'type' => Type::string(),
                'description' => 'descrição da categoria'
            ],
        ];
    }
}
