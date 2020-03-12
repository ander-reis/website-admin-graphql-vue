<?php

declare(strict_types=1);

namespace WebsiteAdmin\GraphQL\Types;

use Carbon\Carbon;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use WebsiteAdmin\GraphQL\Fields\FormattableDate;
use WebsiteAdmin\Models\Noticias;

class NoticiasType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Noticias',
        'description' => 'type notícias',
        'model' => Noticias::class
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'id categoria',
            ],
            'ds_resumo' => [
                'type' => Type::string(),
                'description' => 'resumo da notícia'
            ],
            'ds_texto' => [
                'type' => Type::string(),
                'description' => 'texto da notícia'
            ],
            'ds_palavra_chave' => [
                'type' => Type::string(),
                'description' => 'palavra chave da notícia'
            ],
            'ds_social' => [
                'type' => Type::string(),
                'description' => 'link da rede social da notícia'
            ],
            'fl_status' => [
                'type' => Type::int(),
                'description' => 'status da notícia'
            ],
            'fl_oculta' => [
                'type' => Type::string(),
                'description' => 'status da notícia para o app'
            ],
            'dt_noticia' => [
                'type' => GraphQL::type('date'),
                'description' => 'data da notícia'
            ],

//            'dt_cadastro' => [
//                'type' => Type::string(),
//                'description' => 'created_at',
//            ],

            'dt_cadastro' => new FormattableDate,

//            'created_at' => new FormattableDate,

            'dt_alteracao' => [
                'type' => GraphQL::type('date'),
//                'type' => Type::string(),
                'description' => 'data da alteração da notícia'
            ],
            'categoria' => [
                'type' => GraphQL::type('NoticiasCategoriaType'),
                'description' => 'id da categoria'
            ],
        ];
    }
}
