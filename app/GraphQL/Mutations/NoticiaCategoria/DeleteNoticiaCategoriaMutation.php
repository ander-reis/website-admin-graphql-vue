<?php

declare(strict_types=1);

namespace WebsiteAdmin\GraphQL\Mutations\NoticiaCategoria;

use Closure;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\WrappingType;
use Illuminate\Notifications\Messages\SimpleMessage;
use Illuminate\Support\Collection;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;
use WebsiteAdmin\Models\NoticiasCategoria;

class DeleteNoticiaCategoriaMutation extends Mutation
{
    protected $message = "Resource not found";

    protected $attributes = [
        'name' => 'deleteNoticiaCategoria',
        'description' => 'A mutation'
    ];

    public function type(): Type
    {
        return Type::boolean();
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'id da categoria',
                'rules' => ['required', 'numeric'],
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $fields = $getSelectFields();
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        $result = NoticiasCategoria::destroy($args['id']);

        if(!$result) {
            throw new Error('id não encontrado');
        }

        return $result;
    }
}
