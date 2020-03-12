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

    protected $dates = ['dt_cadastro', 'dt_alteracao'];

    protected $dateFormat = 'Y-m-d H:i:s+';

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
        'fl_oculta',
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

    /**
     * Mutators formata data para o form de edição -> 2000-12-30
     *
     * @return string
     * @throws \Exception
     */
    public function getDtNoticiaUTCFormattedAttribute()
    {
        return \Carbon\Carbon::parse($this->dt_noticia);
    }

    public function getDtNoticiaFormattedAttribute()
    {
        return \Carbon\Carbon::parse($this->dt_noticia)->format('d/m/Y');
    }

    /**
     * Mutators formata hr_expira -> 12:00
     *
     * @return bool|string
     */
    public function getHrNoticiaFormattedAttribute()
    {
        return \Carbon\Carbon::parse($this->dt_noticia)->format('H:i');
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
