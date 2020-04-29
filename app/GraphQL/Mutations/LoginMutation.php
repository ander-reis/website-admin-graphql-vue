<?php

declare(strict_types=1);

namespace WebsiteAdmin\GraphQL\Mutations;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;
use WebsiteAdmin\Exceptions\ResourceNotFoundException;

class LoginMutation extends Mutation
{
    protected $attributes = [
        'name' => 'login',
        'description' => 'A mutation'
    ];

    public function type(): Type
    {
        return \GraphQL::type('login');
    }

    public function args(): array
    {
        return [
            'email' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'email'
            ],
            'password' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'password'
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $fields = $getSelectFields();
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        $credentials = ['email' => $args['email'], 'password' => $args['password']];
        $token = null;
        $token = \JWTAuth::attempt($credentials);

        $user = auth()->user();

        if(!$user) {
            throw new ResourceNotFoundException('Login ou senha incorreto');
        }

        return ['token' => $token,
            'user' => [
                'id' => isset($user) ? $user->id : null,
                'name' => isset($user) ? $user->name : null,
                'email' => isset($user) ? $user->email : null
            ]];
    }
}
