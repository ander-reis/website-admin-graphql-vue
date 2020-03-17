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

class UpdateNoticiaCategoriaMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updateNoticiaCategoria',
        'description' => 'A mutation'
    ];

    public function type(): Type
    {
        return \GraphQL::type('NoticiasCategoriaType');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'id da categoria a ser atualizada',
                'rules' => ['required', 'numeric']
            ],
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

        $result = NoticiasCategoria::where('id', $args['id'])->update(['ds_categoria' => $args['ds_categoria']]);

        if(!$result) {
            throw new Error('recurso não encontrado / alterado');
        }

        return ['id' => $args['id'], 'ds_categoria' => $args['ds_categoria']];
    }
}
