<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Order Model
 */
class Order extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_orders';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'cart_id',
        'customer_id',
        'gateway',
        'gateway_code',
        'hash',
        'shipping_address',
        'shipping_cost',
        'shipping_method',
        'amount'
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'customer' => ['Bedard\Shop\Models\Customer', 'table' => 'bedard_shop_customers'],
        'cart' => ['Bedard\Shop\Models\Cart', 'table' => 'bedard_shop_carts']
    ];

    /**
     * Jsonable shipping address
     */
    public $jsonable = ['shipping_address'];

    /**
     * Query Scopes
     */
    public function scopeIsComplete($query)
    {
        // Returns only orders that have been completed
        $query->where('is_complete', TRUE);
    }

    /**
     * Touches the shipped timestamp
     */
    public function touchShipped()
    {
        $this->shipped = $this->freshTimestamp();
        return $this->save();
    }

    /**
     * Returns the customer's email address
     * @return  string
     */
    public function getCustomerEmailAttribute()
    {
        return $this->customer->email;
    }

    /**
     * Returns the customer's full name
     * @return  string
     */
    public function getCustomerNameAttribute()
    {
        return $this->customer->first_name.' '.$this->customer->last_name;
    }
}