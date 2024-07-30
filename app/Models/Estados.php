<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Estados
 * 
 * @property int $id
 * @property string $estado
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Estados extends Model
{
	protected $table = 'estados';
	public $timestamps = false;

	protected $fillable = [
		'estado'
	];
}
