<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {   
        if ($request->title) {
            $products = Product::where('title', 'like', '%' . $request->title . '%')->paginate(2);
        } 
        else if ($request->date) {
            $products = Product::whereDate('created_at',$request->date)->paginate(2);
        } 
        else if ($request->price_from && $request->price_to) {
            $products = ProductVariantPrice::where('price', '>', $request->price_from)->where('price','<', $request->price_to)->paginate(2);
        }
        else {
            $products = Product::with('variants','variantPrices')->paginate(2);
        }
        $variants = ProductVariant::all();
        $dynamic_color = ProductVariant::select('variant')->distinct()->where('variant_id',1)->get();
        $dynamic_size = ProductVariant::select('variant')->distinct()->where('variant_id',2)->get();
        $dynamic_style = ProductVariant::select('variant')->distinct()->where('variant_id',6)->get();

        return view('products.index')->with("products", $products)->with("variants", $variants)->with("dynamic_color", $dynamic_color)->with("dynamic_size", $dynamic_size)->with("dynamic_style", $dynamic_style);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

    }
    // public function search(Request $request)
    // {
    //     $query = Product::all();
    //     $variants = ProductVariant::all();

    //     if ($request->has('title')) {
    //         $query->where('title', 'like', '%' . $request->title . '%');
    //     }
        
    //     if ($request->has('variant')) {
    //         $query->whereHas('variants', function($variantQuery) use ($request) {
    //             $variantQuery->where('variant', $request->variant);
    //         });
    //     }
        
    //     if ($request->has('price_from')) {
    //         $query->where('price', '>=', $request->price_from);
    //     }
        
    //     if ($request->has('price_to')) {
    //         $query->where('price', '<=', $request->price_to);
    //     }
        
    //     if ($request->has('date')) {
    //         $query->whereDate('created_at', $request->date);
    //     }
        
    //     $products = $query->get();
        
    //     return view('products.index')->with("products", $products)->with("variants", $variants);
    //     }
    

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        
    }
    public function createProduct(Request $request)
    {
        //dd($request->all());
        $product = new Product();
        $product->title = $request->title;
        $product->description = $request->description;
        $product->sku = $request->sku;
        $product->save();

        foreach ($request->product_variant as $product_var) {
            //dd($product_var['tags']);
            // foreach ($product_var->tags as $tag) {
            foreach ($product_var['tags'] as $tag) {
                $productVariant = new ProductVariant();
                $productVariant->variant = $tag;
                $productVariant->variant_id = $product_var['option'];
                $productVariant->product_id = $product->id;
                $productVariant->save();
            }
        }
        foreach ($request->product_variant_prices as $product_var_price) {
            //dd($product_var_price);
            $parts = explode('/', $product_var_price['title']);
            $color = $parts[0];
            $size = $parts[1];
            $style = $parts[2];

            $color = ProductVariant::where('variant', $color)->first();
            if ($color) {
                $color = $color->id;
            } else {
                $color = null;
            }
            $size = ProductVariant::where('variant', $size)->first();
            if ($size) {
                $size = $size->id;
            } else {
                $size = null;
            }
            $style = ProductVariant::where('variant', $style)->first();
            if ($style) {
                $style = $style->id;
            } else {
                $style = null;
            }
            $productVariantPrice = new ProductVariantPrice();
            $productVariantPrice->product_variant_one = $color;
            $productVariantPrice->product_variant_two = $size;
            $productVariantPrice->product_variant_three = $style;
            $productVariantPrice->price = $product_var_price['price'];
            $productVariantPrice->stock = $product_var_price['stock'];
            $productVariantPrice->product_id = $product->id;
            //dd($productVariantPrice);
            $productVariantPrice->save();
            
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
