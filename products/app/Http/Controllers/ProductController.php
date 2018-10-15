<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
        $products=\App\Product::all();
        return view('index',compact('products'));
    }

    public function create()
    {
        return view('create');
    }


    public function edit($id)
    {
        $product = \App\Product::find($id);
        return view('edit',compact('product','id'));
    }

    public function store(Request $request)
    {
            $validatedData = $request->validate([
                'title' => 'required',
                'description' => 'required',
                'image' => 'required',
                'thumbnail' => 'required',
                'price' => 'required|numeric'
        ]);

        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $image_name=time().$file->getClientOriginalName();
            $file->move(public_path().'/images/', $image_name);
        }

        if ($request->hasfile('thumbnail')) {
            $file = $request->file('thumbnail');
            $thumbnail_name=time().$file->getClientOriginalName();
            $file->move(public_path().'/images/', $thumbnail_name);
        }

        $product= new \App\Product;
        $product->title= $request->get('title');
        $product->description= $request->get('description');
        $product->price= $request->get('price');
        $product->image= $image_name;
        $product->thumbnail= $thumbnail_name;
        $product->save();

        return redirect('products')->with('success', 'information added');
    }

    public function update(Request $request, $id)
    {
            $product = \App\Product::find($id);

            if($request->hasFile('image')){
                unlink(public_path().'/images/'.$product->image);
                $file = $request->file('thumbnail');
                $image_name=time().$file->getClientOriginalName();
                $file->move(public_path().'/images/', $image_name);
                $product->file = $image_name;
            }


            if($request->hasFile('thumbnail')){
            unlink(public_path().'/images/'.$product->thumbnail);
            $file = $request->file('thumbnail');
            $thumbnail_name=time().$file->getClientOriginalName();
            $file->move(public_path().'/images/', $thumbnail_name);
            $product->file = $thumbnail_name;
        }
            $product->title = $request->get('title');
            $product->description = $request->get('description');
            $product->price= $request->get('price');
            $product->save();
            return redirect('products');
    }

    public function destroy($id)
    {
        $product = \App\Product::find($id);
        unlink(public_path().'/images/'.$product->image);
        unlink(public_path().'/images/'.$product->thumbnail);
        $product->delete();
        return redirect('products')->with('success','information added');
    }

}
