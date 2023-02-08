<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return Product::latest()->filter(request(['category_name', 'brand_name', 'min_price', 'max_price']))->where('status', Product::STATUS_ACTIVE)->get();

        $products = Product::latest()->filter(request(['category_name', 'brand_name', 'min_price', 'max_price']))->where('status', Product::STATUS_ACTIVE)->get();

        $product_new = [];

        foreach($products as $key => $value) {
            if ($value->image_path) {
                $value->image_path = asset('/storage/'.$value->image_path);
            }
            $product_new[] = $value;
        }
        return $product_new;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'brand_name' => 'required|string',
            'price' => 'required|integer',
            'category_name' => 'required|string',
            'description' => 'required|string',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $original_name = Null;
        $image_path = Null;

        if($request->file('image')) {
            $original_name = $request->file('image')->getClientOriginalName();
            $image_path = $request->file('image')->store('image', 'public');
        }
        

        return Product::create(array_merge($request->all(), ['image_name' => $original_name, 'image_path' => $image_path, 'created_by' => Auth::id()]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ((int)$id && is_int((int)$id)) {
            $product = Product::find($id);
            if ($product) {
                if ($product->image_path) {
                    $product->image_path = asset('/storage/'.$product->image_path);
                }
                return $product;
            }
        } else {
            $product = Product::where('slug', $id)->first();
            if ($product) {
                if ($product->image_path) {
                    $product->image_path = asset('/storage/'.$product->image_path);
                }
                return $product;
            }
        }

        return response(["message" => "Product not found."], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'brand_name' => 'required|string',
            'price' => 'required|integer',
            'category_name' => 'required|string',
            'description' => 'required|string',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $original_name = Null;
        $image_path = Null;
        if($request->file('image')) {
            $original_name = $request->file('image')->getClientOriginalName();

            $image_path = $request->file('image')->store('image', 'public');
        }

        //Get product with given id
        $product = Product::find($id);
        $product->update(array_merge($request->all(), ['image_name' => $original_name, 'image_path' => $image_path, 'updated_by' => Auth::id()]));

        return $product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Hard Delete
        //return Product::destroy($id);

        //Soft Delete
        $result = Product::find($id)->update(['status' => Product::STATUS_INACTIVE]);

        if ($result) {
            return response(["message" => "Product deleted successfully."], 200);
        }
        return response(["message" => "Something went wrong. Please try again later."], 404);
    }
}
