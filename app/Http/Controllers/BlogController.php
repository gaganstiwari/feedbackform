<?php

namespace App\Http\Controllers;
use App\Models\blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Blog::latest()->get();
        return view('blog.index', compact('data'));}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('/blog');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $ValidateData = $request->validate([
            'title' => 'required | string | max:255',
            'discription' => 'required | string | max:2000',
        ]);
          $blog = new blog();
          $blog->title = $ValidateData['title'];
          $blog->discription = $ValidateData['discription'];
          $blog->save();
        
          
          return redirect()->route('blog.index')
          ->with('success', 'Product created successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
