<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    const ROLE_MANAGER = 'manager';
    const ROLE_EMPLOYEE = 'employee';

    const CATALOG_ITEMS_CATEGORY_APARTMENTS = 'apartments';
    const CATALOG_ITEMS_CATEGORY_HOUSES = 'houses';
    const CATALOG_ITEMS_CATEGORY_LANDS = 'lands';
    const CATALOG_ITEMS_CATEGORY_COMMERCIAL_REAL_ESTATE = 'commercial_real_estate';

    use HasApiTokens, Notifiable;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'role' => self::ROLE_MANAGER,
        'preferred_catalog_items_category' => self::CATALOG_ITEMS_CATEGORY_APARTMENTS,
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
        return !is_null($this->company_id);
    }

    /**
     * @param  \App\Company  $company
     * @return bool
     */
    public function isBelongsToCompany(Company $company)
    {
        return $this->company_id == $company->id;
    }

    /**
     * @param  \App\User  $user
     * @return bool
     */
    public function sameCompany(User $user)
    {
        return $this->company_id == $user->company_id;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function employees()
    {
        return is_null($this->company)
            ? []
            : $this->company()->users();
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
