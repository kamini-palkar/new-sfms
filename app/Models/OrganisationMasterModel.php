<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class OrganisationMasterModel extends Model
{
    use HasFactory;
    protected $table = "organisation_master";
    protected $guarded =[];
}
