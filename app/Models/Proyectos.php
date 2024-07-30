<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Proyectos
 * 
 * @property int $id
 * @property string|null $proyecto
 * @property float|null $presupuesto
 * @property int $id_canton
 * @property int|null $id_parroquia
 * @property int|null $id_recinto
 * @property int $id_estado
 * @property int $id_comunidad
 * @property int $id_obra
 * @property string|null $responsable
 * @property string $longitud
 * @property string $latitud
 * @property Carbon|null $fecha_inicio
 * @property Carbon|null $fecha_fin
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class Proyectos extends Model
{
	protected $table = 'proyectos';
	public $timestamps = false;

	protected $casts = [
		'presupuesto' => 'float',
		'id_canton' => 'int',
		'id_parroquia' => 'int',
		'id_recinto' => 'int',
		'id_estado' => 'int',
		'id_comunidad' => 'int',
		'id_obra' => 'int'
	];

	 

	protected $fillable = [
		'proyecto',
		'descripcion',
		'presupuesto',
		'id_canton',
		'id_parroquia',
		'id_recinto',
		'id_estado',
		'id_comunidad',
		'id_obra',
		'responsable',
		'longitud',
		'latitud',
		'fecha_inicio',
		'fecha_fin'
	];
	public function canton()
	{
		return $this->belongsTo(Cantones::class,'id_canton','id');
	}
	public function parroquia()
	{
		return $this->belongsTo(Parroquias::class,'id_parroquia','id');
	}
	public function recinto()
	{
		return $this->belongsTo(Recintos::class,'id_recinto','id');
	}
	public function comunidad()
	{
		return $this->belongsTo(Comunidades::class,'id_comunidad','id');
	}
	public function estado()
	{
		return $this->belongsTo(Estados::class,'id_estado','id');
	}
	public function obra()
	{
		return $this->belongsTo(Obras::class,'id_obra','id');
	}

	public function imagen()
    {
        return $this->morphMany(Imageable::class, 'imageable');
    }

}
