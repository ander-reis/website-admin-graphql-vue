<?php

namespace WebsiteAdmin\Models;

use WebsiteAdmin\Models\NoticiasCategoria;
use Illuminate\Database\Eloquent\Model;

class Noticias extends Model
{
    /**
     * conexão novo database
     *
     * @var string
     */
//    protected $connection = 'sqlsrv-website';

    /**
     * table
     *
     * @var string
     */
    protected $table = 'tb_sinpro_noticias';

    /**
     * set created_at
     */
    const CREATED_AT = 'dt_cadastro';

    /**
     * set updated_at
     */
    const UPDATED_AT = 'dt_alteracao';

//    protected $dates = ['dt_noticia', 'dt_cadastro', 'dt_alteracao'];
//    protected $dates = ['dt_noticia'];

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $casts = [
//        'dt_cadastro' => 'date:Y-m-d',
//        'dt_noticia' => 'datetime:Y-m-d H:i:s',
//        'dt_cadastro' => 'datetime:Y-m-d H:i:s',
//        'dt_alteracao' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_noticia',
        'id_categoria',
        'dt_noticia',
        'dt_cadastro',
        'dt_alteracao',
        'ds_resumo',
        'ds_texto',
        'ds_palavra_chave',
        'ds_social',
        'fl_status',
    ];

    /**
     * set logging
     */
//    protected static $logAttributes = [
//        'id_categoria',
//        'dt_cadastro',
//        'dt_alteracao',
//        'dt_noticia',
//        'ds_resumo',
//        'ds_texto',
//        'ds_palavra_chave',
//        'ds_social',
//        'fl_status'
//    ];

    /**
     * set log fillable
     *
     * @var bool
     */
//    protected static $logFillable = true;

    /**
     * set log name
     *
     * @var string
     */
//    protected static $logName = 'noticias';

    public static function convertDsPalavraChave($string)
    {
        return implode(', ', $string);
    }

    /**
     * Relacionamento noticias para categorias, um para um
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categoria()
    {
        return $this->belongsTo(NoticiasCategoria::class, 'id_categoria');
    }
}
