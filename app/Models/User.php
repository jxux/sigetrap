<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\System\Catalogs\Country;
use App\Models\System\Catalogs\Department;
use App\Models\System\Catalogs\District;
use App\Models\System\Catalogs\IdentityDocumentType;
use App\Models\System\Catalogs\Province;

class User extends Authenticatable{

    use HasFactory, Notifiable, HasRoles;

    protected $with = ['identity_document_type', 'country', 'department', 'province', 'district'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'dni',
        'nick_name',
        'identity_document_type_id',
        'sex',
        'date_birth',
        'country_id',
        'department_id',
        'province_id',
        'district_id',
        'telephone',
        'address',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //Relacion uno a muchos
    public function posts(){
        return $this->hasMany(Post::class);
    }

    public function identity_document_type(){
        return $this->belongsTo(IdentityDocumentType::class, 'identity_document_type_id');
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function province(){
        return $this->belongsTo(Province::class);
    }

    public function district(){
        return $this->belongsTo(District::class);
    }
}
