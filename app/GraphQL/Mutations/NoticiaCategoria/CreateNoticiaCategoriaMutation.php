<?php

declare(strict_types=1);

namespace WebsiteAdmin\GraphQL\Mutations\NoticiaCategoria;

use Closure;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;
use WebsiteAdmin\Models\NoticiasCategoria;

class CreateNoticiaCategoriaMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createCategoria',
        'description' => 'A mutation'
    ];

    public function type(): Type
    {
        return \GraphQL::type('NoticiasCategoriaType');
    }

    public function args(): array
    {
        return [
            'ds_categoria' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'descrição da categoria da notícia',
                'rules' => ['required', 'min:3', 'max:50']
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $fields = $getSelectFields();
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        $result =  NoticiasCategoria::create([
            'ds_categoria' => $args['ds_categoria'],
        ]);

        if(!$result) {
            throw new Error('não foi possível cadastrar a categoria');
        }

        return ['id' => $result->id, 'ds_categoria' => $result->ds_categoria];
    }
}
