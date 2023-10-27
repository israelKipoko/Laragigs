<?php

namespace App\Http\Controllers;

use App\Models\Listings;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingsController extends Controller
{
    //Show all listings
    public function index(){
        return view('listings.index', [
            'listings' => Listings::latest()->filter(request(['tag','search']))->paginate(6)
        ]);
    }
    //Show single listing
    public function show(Listings $listing){
        return view('listings.show', [
            'listings' => $listing
        ]);
    }

    //Show Create form
    public function create(){
        return view('listings.create');
    }

    //Store listing data
    public function store(Request $request){
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings','company')],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required','email'],
            'tags' => 'required',
            'description' => 'required'
        ]);

        if($request->hasFile('logo')){
            $formFields['logo'] = $request->file('logo')->store('logos','public');
        }

        $formFields['user_id'] = auth()->id();

        Listings::create($formFields);
    
        return redirect('/')->with('message','Listing created succesfully');
    }

    //Show edit form
    public function edit(Listings $listing){
        return view('listings.edit', ['listing' => $listing]);
    }

    //Update listing
    public function update(Request $request, Listings $listing){
       
        //Make sure logged in user is owner
        if($listing->user_id != auth()->id()){
            abort(403, 'Unauthorized Action');
        }
       
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required'],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required','email'],
            'tags' => 'required',
            'description' => 'required'
        ]);

        if($request->hasFile('logo')){
            $formFields['logo'] = $request->file('logo')->store('logos','public');
        }
        $listing->update($formFields);
    
        return back()->with('message','Listing updated succesfully!');
    }

    //Delete listing
    public function delete(Listings $listing){
                  //Make sure logged in user is owner
          if($listing->user_id != auth()->id()){
            abort(403, 'Unauthorized Action');
        }

        $listing->delete();
        return redirect('/')->with('message','Listing deleted succesfully!');
    }

    //Manage listings
    public function manage(){
        return view('listings.manage', ['listings' => auth()->user()->listings()->get()]);
    }
}
