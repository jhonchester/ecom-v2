<?php
namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Cookie;

class CartManagement{
    
    static public function addItemToCart($product_id){
    $cart_items = self::getCartItemsFromCookie();

    $existing_item = null;

    foreach ($cart_items as $key => $item) {
        if ($item['product_id'] == $product_id) {
            $existing_item = $key;
            break;
        }
    }

    if ($existing_item !== null) {
        $cart_items[$existing_item]['quantity']++;
        $cart_items[$existing_item]['total_amount'] = $cart_items[$existing_item]['quantity'] * $cart_items[$existing_item]['unit_amount'];
    } else {
        $product = Product::where('id', $product_id)->first(['id', 'name', 'price', 'images']);
        if ($product) {
            $cart_items[] = [
                'product_id' => $product_id,
                'name' => $product->name,
                'image' => $product->images[0],
                'quantity' => 1,
                'unit_amount' => $product->price,
                'total_amount' => $product->price
            ];
        }
    }

    self::addCartItemsToCookie($cart_items);

    // Return total quantity of items
    return array_sum(array_column($cart_items, 'quantity'));
}

static public function addItemToCartWithQty($product_id, $qty = 1)
{
    $cart_items = self::getCartItemsFromCookie();

    $existing_item = null;

    foreach ($cart_items as $key => $item) {
        if ($item['product_id'] == $product_id) {
            $existing_item = $key;
            break;
        }
    }

    if ($existing_item !== null) {
        $cart_items[$existing_item]['quantity'] = $qty;
        $cart_items[$existing_item]['total_amount'] = $cart_items[$existing_item]['quantity'] * $cart_items[$existing_item]['unit_amount'];
    } else {
        $product = Product::where('id', $product_id)->first(['id', 'name', 'price', 'images']);
        if ($product) {
            $cart_items[] = [
                'product_id' => $product_id,
                'name' => $product->name,
                'image' => $product->images[0],
                'quantity' => $qty,
                'unit_amount' => $product->price,
                'total_amount' => $product->price
            ];
        }
    }

    self::addCartItemsToCookie($cart_items);

    // Return total quantity of items
    return array_sum(array_column($cart_items, 'quantity'));
}


public static function removeCartItem($product_id)
{
    $cart_items = self::getCartItemsFromCookie();

    foreach ($cart_items as $key => $item) {
        if ($item['product_id'] == $product_id) {
            unset($cart_items[$key]);
        }
    }

    // Reindex the array to avoid gaps in keys
    $cart_items = array_values($cart_items);

    self::addCartItemsToCookie($cart_items);

    return $cart_items; // Always return a properly indexed array
}
    
    
    
    
    static public function addCartItemsToCookie($cart_items){
        Cookie::queue('cart_items', json_encode($cart_items), 60*24*30);
    }

    static public function clearCartItems($cart_items){
        Cookie::queue(Cookie::forget('cart_items'));
    }

    public static function getCartItemsFromCookie()
{
    $cart_items = json_decode(Cookie::get('cart_items'), true);
    return $cart_items ?: [];
}

    static public function incrementQuantityToCartItem($product_id){
        $cart_items = self::getCartItemsFromCookie();
        foreach($cart_items as $key => $item){
            if($item['product_id'] == $product_id){
                $cart_items[$key]['quantity']++;
                $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] * $cart_items[$key]['unit_amount'];
            }
        }

        self::addCartItemsToCookie($cart_items);
        return $cart_items;
    }

    static public function decrementQuantityToCartItem($product_id){
        $cart_items = self::getCartItemsFromCookie();
        foreach($cart_items as $key => $item){
            if($item['product_id'] == $product_id){
                if($cart_items[$key]['quantity'] > 1){
                    $cart_items[$key]['quantity']--;
                    $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] * $cart_items[$key]['unit_amount'];
                }
            }
        }

        self::addCartItemsToCookie($cart_items);
        return $cart_items;
    }

    public static function calculateGrandTotal($items)
{
    // Ensure $items is an array
    if (!is_array($items) || empty($items)) {
        return 0; // If $items is not an array or is empty, return 0
    }

    // Handle cases where $items is not an array of arrays
    foreach ($items as $item) {
        if (!is_array($item)) {
            return 0; // If any $item is not an array, return 0
        }
    }

    // Calculate the grand total
    return array_sum(array_column($items, 'total_amount'));
}
}