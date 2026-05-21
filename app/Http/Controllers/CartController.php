<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuSpecialItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $isAjax = false;

    protected function detectAjax()
    {
        $this->isAjax = request()->expectsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest';
    }

    protected function jsonResponse($data, $status = 200)
    {
        return response()->json($data, $status);
    }

    protected function redirectOrJson($redirect, $data)
    {
        if ($this->isAjax) {
            return $this->jsonResponse($data);
        }
        return redirect($redirect)->with($data['message'] ? 'success' : 'error', $data['message'] ?: $data['error']);
    }

    /**
     * Display the cart
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = $this->calculateTotal($cart);

        return view('frontend.cart.index', compact('cart', 'total'));
    }

    /**
     * Add regular menu to cart (AJAX support)
     */
    public function add(Request $request, $id)
    {
        $this->detectAjax();
        $menu = Menu::findOrFail($id);

        if (!$menu->is_available) {
            if ($this->isAjax) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Menu tidak tersedia!'
                ], 400);
            }
            return redirect()->back()->with('error', 'Menu tidak tersedia!');
        }

        $cart = session()->get('cart', []);
        $qty = max(1, (int) $request->input('qty', 1));

        if (isset($cart[$id])) {
            $cart[$id]['qty'] += $qty;
        } else {
            $cart[$id] = [
                'id' => $menu->id,
                'nama' => $menu->nama_menu,
                'harga' => $menu->harga,
                'gambar' => $menu->gambar,
                'qty' => $qty,
                'type' => 'menu'
            ];
        }

        session()->put('cart', $cart);
        $cartData = $this->getCartData($cart);

        if ($this->isAjax) {
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Menu berhasil ditambahkan ke keranjang!',
                'cart' => $cartData,
                'item' => $cart[$id]
            ]);
        }

        return redirect()->back()->with('success', 'Menu berhasil ditambahkan ke keranjang!');
    }

    /**
     * Add special menu item to cart (AJAX support)
     */
    public function addSpecial(Request $request, $id)
    {
        $this->detectAjax();
        $item = MenuSpecialItem::findOrFail($id);

        if (!$item->is_available) {
            if ($this->isAjax) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Menu special tidak tersedia!'
                ], 400);
            }
            return redirect()->back()->with('error', 'Menu special tidak tersedia!');
        }

        $cart = session()->get('cart', []);
        $qty = max(1, (int) $request->input('qty', 1));
        $cartKey = 'special_' . $id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] += $qty;
        } else {
            $cart[$cartKey] = [
                'id' => $item->id,
                'nama' => $item->name,
                'harga' => $item->price,
                'gambar' => $item->image,
                'qty' => $qty,
                'type' => 'special',
                'menu_special_id' => $item->menu_special_id
            ];
        }

        session()->put('cart', $cart);
        $cartData = $this->getCartData($cart);

        if ($this->isAjax) {
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Menu special berhasil ditambahkan ke keranjang!',
                'cart' => $cartData,
                'item' => $cart[$cartKey]
            ]);
        }

        return redirect()->back()->with('success', 'Menu special berhasil ditambahkan ke keranjang!');
    }

    /**
     * Remove item from cart
     */
    public function remove($id)
    {
        $this->detectAjax();
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        $cartData = $this->getCartData($cart);

        if ($this->isAjax) {
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Item dihapus dari keranjang!',
                'cart' => $cartData
            ]);
        }

        return redirect()->back()->with('success', 'Item dihapus dari keranjang!');
    }

    /**
     * Update item quantity in cart
     */
    public function update(Request $request, $id)
    {
        $this->detectAjax();
        $cart = session()->get('cart', []);
        $qty = (int) $request->input('qty', 1);

        if (isset($cart[$id])) {
            if ($qty <= 0) {
                unset($cart[$id]);
            } else {
                $cart[$id]['qty'] = $qty;
            }
            session()->put('cart', $cart);
        }

        $cartData = $this->getCartData($cart);

        if ($this->isAjax) {
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Quantity berhasil diupdate!',
                'cart' => $cartData
            ]);
        }

        return redirect()->back()->with('success', 'Quantity berhasil diupdate!');
    }

    /**
     * Increase item quantity
     */
    public function increment($id)
    {
        $this->detectAjax();
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['qty']++;
            session()->put('cart', $cart);
        }

        $cartData = $this->getCartData($cart);

        if ($this->isAjax) {
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Quantity berhasil ditambah!',
                'cart' => $cartData
            ]);
        }

        return redirect()->back()->with('success', 'Quantity berhasil ditambah!');
    }

    /**
     * Decrease item quantity
     */
    public function decrement($id)
    {
        $this->detectAjax();
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['qty']--;

            if ($cart[$id]['qty'] <= 0) {
                unset($cart[$id]);
            }

            session()->put('cart', $cart);
        }

        $cartData = $this->getCartData($cart);

        if ($this->isAjax) {
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Quantity berhasil dikurangi!',
                'cart' => $cartData
            ]);
        }

        return redirect()->back()->with('success', 'Quantity berhasil dikurangi!');
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        $this->detectAjax();
        session()->forget('cart');
        $cartData = $this->getCartData([]);

        if ($this->isAjax) {
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Keranjang berhasil dikosongkan!',
                'cart' => $cartData
            ]);
        }

        return redirect()->back()->with('success', 'Keranjang berhasil dikosongkan!');
    }

    /**
     * Get cart count and total for display
     */
    public function count()
    {
        $cart = session()->get('cart', []);
        $count = 0;
        $total = 0;

        foreach ($cart as $item) {
            $count += $item['qty'];
            $total += $item['harga'] * $item['qty'];
        }

        return response()->json([
            'count' => $count,
            'total' => $total,
            'total_formatted' => 'Rp ' . number_format($total, 0, ',', '.')
        ]);
    }

    /**
     * Get cart data for checkout
     */
    public function getCartForCheckout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return null;
        }

        $total = $this->calculateTotal($cart);

        return [
            'items' => $cart,
            'total' => $total,
            'count' => array_sum(array_column($cart, 'qty'))
        ];
    }

    /**
     * Calculate total price from cart
     */
    protected function calculateTotal(array $cart): float
    {
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['harga'] * $item['qty'];
        }

        return $total;
    }

    /**
     * Get cart data with count, total, and items
     */
    protected function getCartData(array $cart): array
    {
        $count = 0;
        $total = 0;

        foreach ($cart as $item) {
            $count += $item['qty'];
            $total += $item['harga'] * $item['qty'];
        }

        return [
            'count' => $count,
            'total' => $total,
            'total_formatted' => 'Rp ' . number_format($total, 0, ',', '.'),
            'items' => $cart
        ];
    }
}
