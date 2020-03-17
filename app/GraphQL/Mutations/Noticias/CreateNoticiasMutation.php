<?php

declare(strict_types=1);

namespace WebsiteAdmin\GraphQL\Mutations\Noticias;

use Closure;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;
use WebsiteAdmin\GraphQL\Fields\FormattableDate;
use WebsiteAdmin\Models\Noticias;

class CreateNoticiasMutation extends Mutation
{
    protected $attributes = [
        'name' => 'noticias\CreateNoticias',
        'description' => 'A mutation'
    ];

    public function type(): Type
    {
        return \GraphQL::type('NoticiasType');
    }

    public function args(): array
    {
        return [
            'ds_resumo' => [
                'type' => Type::string(),
                'description' => 'resumo da notícia',
                'rules' => ['required', 'min:3', 'max:80']
            ],
            'ds_texto' => [
                'type' => Type::string(),
                'description' => 'texto da notícia',
                'rules' => ['required']
            ],
            'ds_palavra_chave' => [
                'type' => Type::listOf(Type::string()),
                'description' => 'palavra chave da notícia',
                'rules' => ['max:150']
            ],
            'ds_social' => [
                'type' => Type::string(),
                'description' => 'link da rede social da notícia'
            ],
            'fl_status' => [
                'type' => Type::int(),
                'description' => 'status da notícia',
                'rules' => ['required']
            ],
//            'dt_noticia' => new FormattableDate,
            'dt_noticia' => [
                'type' => \GraphQL::type('date'),
                'description' => 'data da notícia',
                'rule' => ['required']
            ],
            'id_categoria' => [
                'type' => Type::id(),
                'description' => 'id categoria'
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $fields = $getSelectFields();
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        $args['ds_palavra_chave'] = implode(', ', $args['ds_palavra_chave']);

//        dd($args);

        $result = Noticias::create($args);

        $args['id_noticia'] = $result->id;

        Noticias::where('id', $result->id)->update($args);

        if(!$result) {
            throw new Error('não foi possível cadastrar a categoria');
        }

        return $result;
    }
}
