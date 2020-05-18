<?php

declare(strict_types=1);

namespace WebsiteAdmin\GraphQL\Mutations\Noticias;

use Closure;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;
use WebsiteAdmin\Models\Noticias;

class UpdateNoticiasMutation extends Mutation
{
    protected $attributes = [
        'name' => 'noticias\UpdateNoticias',
        'description' => 'A mutation'
    ];

    public function type(): Type
    {
        return \GraphQL::type('NoticiasType');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'id da notícia a ser atualizada',
                'rules' => ['required', 'numeric']
            ],
            'ds_resumo' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'resumo da notícia',
                'rules' => ['required', 'min:3', 'max:80']
            ],
            'ds_texto' => [
                'type' => Type::nonNull(Type::string()),
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
                'type' => Type::nonNull(Type::int()),
                'description' => 'status da notícia',
                'rules' => ['required']
            ],
            'dt_noticia' => [
                'type' => \GraphQL::type('date'),
                'description' => 'data da notícia',
                'rule' => ['required']
            ],
            'ds_data' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'data da notícia, **OBS: Y-m-d',
                'rule' => ['required']
            ],
            'ds_hora' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'hora da notícia, **OBS: H:i',
                'rule' => ['required']
            ],
            'id_categoria' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'id categoria'
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $fields = $getSelectFields();
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        $args['dt_noticia'] = $args['ds_data'] . ' ' . $args['ds_hora'];
        unset($args['ds_data'], $args['ds_hora']);

        $args['ds_palavra_chave'] = Noticias::convertDsPalavraChave($args['ds_palavra_chave']);

        $noticia = Noticias::findOrFail($args['id']);

        if(!$noticia) {
            throw new Error('não foi possível alterar a notícia');
        }

        $noticia->fill($args);
        $noticia->save();

        return $noticia;
    }
}
