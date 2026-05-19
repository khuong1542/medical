<?php

namespace App\Models;

use Illuminate\Console\Attributes\Description;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'code', 'name', 'type', 'images', 'tax_code', 'address', 'tel', 'email', 'website', 'map_url', 'description', 'order', 'status', 'created_by', 'updated_by', 'deleted_by', 'created_at', 'updated_at'])]
#[Description("Cơ sở y tế")]
class Facility extends Model
{
	public $incrementing = false;

	public $sortable = ['order'];

	public $casts = [
		'status' => 'boolean',
	];
}
