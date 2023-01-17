<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    // show all listing
    public function index()
    {
        return view('listings.index', [
            'listings' => Listing::latest()->filter(request(['tag','search']))->paginate(6)
        ]);
    }

    //show single listing
    public function show(Listing $listing)
    {
        return view('listings.show', [
            'listing' => $listing
        ]);
    }

    public function create()
    {
        return view('listings.create');
    }

    //store listing data
    public function store(Request $request)
    {
        // dd($request);
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings', 'company')],
            'location' => 'required',
            'website' => 'required','website',
            'email' => ['required','email'],
            'tags' => 'required',
            'description' => 'required',
            'deadline' => ['nullable']
        ]);

        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        //add user relation to the database
        $formFields['user_id'] = auth()->id();

        Listing::create($formFields);

        return redirect()->route('index')->with('message', 'Listing Creatd Successfully');
    }

    public function edit(Listing $listing)
    {
        return view('listings.edit', ['listing' => $listing]);
    }

    //update/edit
    public function update(Request $request, Listing $listing)
    {
        //only logged in user can edit
        if ($listing->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action');
        }

        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required'],
            'location' => 'required',
            'website' => 'required','website',
            'email' => ['required','email'],
            'tags' => 'required',
            'description' => 'required',
            'deadline' => 'nullable',
            'deadline' => ['nullable','datetime:d/m/y']
        ]);



        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $listing->update($formFields);

        return back()->with('message', 'Listing Updated Successfully');
    }

    //delete listing
    public function delete(Listing $listing)
    {
        //only logged in user can delete
        if ($listing->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action');
        }

        $listing->delete();
        return redirect()->route('index')->with('message', 'Listing Deleted Successfully');
    }


    //manage method
    public function manage()
    {
        return view('listings.manage', ['listings' => auth()->user()->listings()->get()]);
    }
}
