<?php namespace Bedard\Shop;

use Backend;
use System\Classes\PluginBase;

/**
 * Shop Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     * @return  array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Shop',
            'description' => 'An ecommerce platform for OctoberCMS.',
            'author'      => 'Scott Bedard',
            'icon'        => 'icon-shopping-cart'
        ];
    }

    /**
     * Returns backend navigation
     * @return  array
     */
    public function registerNavigation()
    {
        return [
            'shop' => [
                'label'         => 'Shop',
                'url'           => Backend::url('bedard/shop/orders'),
                'icon'          => 'icon-shopping-cart',
                'permissions'   => ['bedard.shop.*'],
                'order'         => 500,

                'sideMenu' => [
                    'orders' => [
                        'label'         => 'Orders',
                        'icon'          => 'icon-clipboard',
                        'url'           => Backend::url('bedard/shop/orders'),
                        'permissions'   => ['bedard.shop.access_orders']
                    ],
                    'customers' => [
                        'label'         => 'Customers',
                        'icon'          => 'icon-users',
                        'url'           => Backend::url('bedard/shop/customers'),
                        'permissions'   => ['bedard.shop.access_customers']
                    ],
                    'products' => [
                        'label'         => 'Products',
                        'icon'          => 'icon-cubes',
                        'url'           => Backend::url('bedard/shop/products'),
                        'permissions'   => ['bedard.shop.access_products']
                    ],
                    'categories' => [
                        'label'         => 'Categories',
                        'icon'          => 'icon-folder-o',
                        'url'           => Backend::url('bedard/shop/categories'),
                        'permissions'   => ['bedard.shop.access_categories']
                    ],
                    'discounts' => [
                        'label'         => 'Discounts',
                        'icon'          => 'icon-clock-o',
                        'url'           => Backend::url('bedard/shop/discounts'),
                        'permissions'   => ['bedard.shop.access_discounts']
                    ],
                    'coupons' => [
                        'label'         => 'Coupons',
                        'icon'          => 'icon-code',
                        'url'           => Backend::url('bedard/shop/coupons'),
                        'permissions'   => ['bedard.shop.access_coupons']
                    ],
                    'settings' => [
                        'label'         => 'Settings',
                        'icon'          => 'icon-cog',
                        'url'           => Backend::url('system/settings/update/bedard/shop/settings'),
                        'permissions'   => ['bedard.shop.access_settings']
                    ]
                ]
            ]
        ];
    }

    /**
     * Returns form widgets
     * @return  array
     */
    public function registerFormWidgets()
    {
        return [
            'Bedard\Shop\Widgets\Arrangement' => [
                'label' => 'Product Arrangement',
                'code'  => 'arrangement'
            ]
        ];
    }

    /**
     * Registers plugin settings
     * @return  array
     */
    public function registerSettings()
    {
        return [
            'settings' => [
                'label'         => 'Settings',
                'category'      => 'Shop',
                'icon'          => 'icon-shopping-cart',
                'description'   => 'Configure general shop settings.',
                'class'         => 'Bedard\Shop\Models\Settings',
                'order'         => 100,
                'keywords'      => 'shop'
            ],
            'paysettings' => [
                'label'         => 'Payment Settings',
                'category'      => 'Shop',
                'icon'          => 'icon-money',
                'description'   => 'Configure payment settings.',
                'class'         => 'Bedard\Shop\Models\PaySettings',
                'order'         => 200,
                'keywords'      => 'shop'
            ]
        ];
    }

    /**
     * Register plugin components
     * @return  array
     */
    public function registerComponents()
    {
        return [
            'Bedard\Shop\Components\Cart'                   => 'shopCart',
            'Bedard\Shop\Components\Categories'             => 'shopCategories',
            'Bedard\Shop\Components\Category'               => 'shopCategory',
            'Bedard\Shop\Components\PaypalExpressCallback'  => 'shopPaypalExpressCallback',
            'Bedard\Shop\Components\PaypalExpressCheckout'  => 'shopPaypalExpressCheckout', 
            'Bedard\Shop\Components\Product'                => 'shopProduct'
        ];
    }


    /**
     * Registers report widgets
     * @return  array
     */
    public function registerReportWidgets()
    {
        return [
            'Bedard\Shop\ReportWidgets\HeatMap' => [
                'label'     => 'Shop Overview',
                'context'   => 'dashboard'
            ],
        ];
    }
}
