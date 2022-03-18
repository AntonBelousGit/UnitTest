<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\UpdateProductRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $products = Product::paginate(10);

        return view('products.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $product = Product::create($data);

        if ($request->hasFile('photo'))
        {

            $filename = $request->photo->getClientOriginalName();
            $request->photo->storeAs('logos',$filename);
            $product->update(['photo'=>$filename]);
        }
        return redirect()->route('product.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return void
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Product $product
     * @return Application|Factory|View
     */
    public function edit(Product $product)
    {
        return  view('products.edit',compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProductRequest $request
     * @param Product $product
     * @return RedirectResponse
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->all();
        $product->update($data);
        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return RedirectResponse
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return  redirect()->route('product.index');
    }


}
