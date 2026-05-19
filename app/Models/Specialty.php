<?php

namespace App\Models;

use Illuminate\Console\Attributes\Description;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'code', 'name', 'order', 'status', 'created_at', 'updated_at'])]
#[Description("Chuyên khoa")]
class Specialty extends Model
{
	protected $table = 'specialties';

	public $incrementing = false;

	public $sortable = ['order'];

	public $casts = [
		'status' => 'boolean',
	];
}