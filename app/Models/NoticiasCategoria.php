<?php

namespace WebsiteAdmin\Models;

use WebsiteAdmin\Models\Noticias;
use Illuminate\Database\Eloquent\Model;

class NoticiasCategoria extends Model
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
    protected $table = 'tb_sinpro_noticias_categorias';

    /**
     * set datetime
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ds_categoria'];

    /**
     * set logging
     */
    protected static $logAttributes = [
        'ds_categoria'
    ];

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
//    protected static $logName = 'noticias_categorias';

    /**
     * Relacionamento categoria para noticias, um para muitos
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function noticias()
    {
        return $this->hasMany(Noticias::class, 'id_categoria');
    }
}
