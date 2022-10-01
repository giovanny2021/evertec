<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * Atributos de la Base de Datos
     *
     * @var array<string,string,string,string>
     */
    protected $fillable = ['customer_name','customer_email','customer_mobile','status'];

    /**
     * Atributos de Creacion y Actulizacion
     *
     * @var array<timestamp,timestamp>
     */
    protected $date = ['created_at','updated_at'];
}
