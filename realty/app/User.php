<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Filterable;

class User extends Authenticatable
{
    const ROLE_MANAGER = 'manager';
    const ROLE_EMPLOYEE = 'employee';

    use HasApiTokens, Notifiable, Filterable;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'role' => self::ROLE_MANAGER,
        'preferred_catalog_items_category' => CatalogItem::CATEGORY_APARTMENTS,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'image_id',
        'role',
        'f_name',
        'm_name',
        'l_name',
        'email',
        'password',
        'preferred_catalog_items_category',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return bool
     */
    public function isManager()
    {
        return $this->role == self::ROLE_MANAGER;
    }

    /**
     * @return bool
     */
    public function isEmployee()
    {
        return $this->role == self::ROLE_EMPLOYEE;
    }

    /**
     * @return bool
     */
    public function hasCompany()
    {
        return (bool) $this->company_id;
    }

    /**
     * @param  \App\Company  $company
     * @return bool
     */
    public function isBelongsToCompany(Company $company)
    {
        return $this->company_id === $company->id;
    }

    /**
     * @param  \App\User  $user
     * @return bool
     */
    public function isSameCompany(User $user)
    {
        return $this->company_id === $user->company_id;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function image()
    {
        return $this->hasOne('App\Image');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favorites()
    {
        return $this->belongsToMany('App\CatalogItem', 'favorites')->withTimestamps();
    }
}
