<?php

declare(strict_types=1);

namespace WebsiteAdmin\GraphQL\Queries;

use Closure;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use WebsiteAdmin\Models\Noticias;

class NoticiasQuery extends Query
{
    protected $attributes = [
        'name' => 'noticias',
        'description' => 'select notícias'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('NoticiasType'));
    }

    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        $user = \JWTAuth::parseToken()->toUser();
        return $user ? true : false;
    }

    public function getAuthorizationMessage(): string
    {
        return 'You are not authorized to perform this action';
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'id da notícia'
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /** @var SelectFields $fields */
        $fields = $getSelectFields();
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        if (isset($args['id'])) {
            $noticias = Noticias::where('id', $args['id'])->get();
        }

        if(!isset($args['id'])) {
            $noticias = Noticias::all();
        }

        return $noticias;
    }
}
