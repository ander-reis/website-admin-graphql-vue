<?php

declare(strict_types=1);

namespace WebsiteAdmin\GraphQL\Queries;

use Closure;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use WebsiteAdmin\Models\Account;

class AccountQuery extends Query
{
    protected $attributes = [
        'name' => 'account',
        'description' => 'A query'
    ];

//    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
//    {
//        $user = \JWTAuth::parseToken()->toUser();
//        return $user ? true : false;
//    }

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Accounts'));
    }

    public function args(): array
    {
//         return Type::nonNull(Type::listOf(Type::nonNull(GraphQL::type('accounts'))));
        return [];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /** @var SelectFields $fields */
        $fields = $getSelectFields();
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        $accounts = Account::all();

        return $accounts;
    }
}
