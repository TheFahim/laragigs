<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;


class ListingController extends Controller
{
    // show all listing
    public function index(){
        return view('listings.index',[
            'listings' => Listing::latest()->filter(request(['tag','search']))->paginate(6)
        ]);
    }

    //show single listing
    public function show(Listing $listing){
        return view('listings.show',[
            'listing' => $listing
        ]);
    }

    public function create(){
        return view('listings.create');
    }

    //store listing data
    public function store(Request $request){
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings','company')],
            'location' => 'required',
            'website' => 'required','website',
            'email' => ['required','email'],
            'tags' => 'required',
            'description' => 'required'
        ]); 

        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos','public');
        }

        Listing::create($formFields);

        return redirect('/')->with('message','Listing Creatd Successfully');
    }

    public function edit(){
        
    }
}
