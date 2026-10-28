<?php

namespace Modules\Cart\Helpers;

use Illuminate\Support\Facades\Facade;
/**
 * Class Cart
 * @package  App\Helpers\Cart
 * @method static bool has($id)
 * @method static Collection all();
 * @method static array get($id);
 * @method static Cart put(array $value, Model $obj = null);
 * @method static Cart instance(string $name);
 * @method static Cart flush();
 * @method static Cart update($id, mixed $options);
 * @method static Number count($id);
 * @method static bool delete($id);
 * @method static Number totalCount();
 */

class Cart extends Facade
{
    protected static function getFacadeAccessor(){
        return 'cart';
    }
}