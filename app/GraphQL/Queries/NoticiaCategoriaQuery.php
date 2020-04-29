<?php

declare(strict_types=1);

namespace WebsiteAdmin\GraphQL\Queries;

use Closure;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use WebsiteAdmin\Models\NoticiasCategoria;

class NoticiaCategoriaQuery extends Query
{
    protected $attributes = [
        'name' => 'noticiaCategoria',
        'description' => 'select NoticiaCategoria'
    ];

    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        $user = \JWTAuth::parseToken()->toUser();
        return $user ? true : false;
    }

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('NoticiasCategoriaType'));
    }

    public function args(): array
    {
        return [
            'id' => [
              'type' => Type::id(),
              'description' => 'id da categoria'
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /** @var SelectFields $fields */
        $fields = $getSelectFields();
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        if(isset($args['id'])) {
            $noticiaCategoria = NoticiasCategoria::where('id', $args['id'])->get();

        }

        if(!isset($args['id'])) {
            $noticiaCategoria = NoticiasCategoria::all();
        }

        return $noticiaCategoria;
    }
}
