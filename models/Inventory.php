<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Inventory Model
 */
class Inventory extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_inventories';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [
        'product' => ['Bedard\Shop\Models\Product', 'table' => 'bedard_shop_products']
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    /**
     * Query Scopes
     */
    public function scopeIsActive($query)
    {
        $query->where('is_active', true);
    }
}