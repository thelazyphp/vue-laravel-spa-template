<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    const TYPE_OWNER = 'owner';
    const TYPE_AGENT = 'agent';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'type' => self::TYPE_OWNER,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'name',
        'email',
        'phone',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function catalogItems()
    {
        return $this->hasMany('App\CatalogItem');
    }
}
