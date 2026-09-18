<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

    public function administration(Group $group)
    {

        if (! Auth::user()->group?->is_admin) {
            abort(403, 'Unauthorized Action!');
        }

        $groups_data = Group::query()
            ->orderBy('id', 'asc')
            ->paginate(5)
            ->withQueryString();

        return view('groups.administration', ['groups_data' => $groups_data]);
    }
    public function edit(Group $group)
    {
        if (! Auth::user()->group?->is_admin) {
            abort(403, 'Unauthorized Action!');
        }

        return view('groups.edit', ['group' => $group, 'backUrl' => url()->previous()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
    {
        if (! Auth::user()->group?->is_admin) {
            abort(403, 'Unauthorized Action!');
        }

        $formFields = $request->validate(
            [
                'name' => 'required|string|max:30|min:5',
                'description' => 'required|string|max:30|min:5',
            ],
            [
                'name.required' => 'Please write a name!',
                'name.max' => 'Name must be 30 characters or less.',
                'description.required' => 'Please write a description!',
                'description.max' => 'Name must be 30 characters or less.'

            ]
        );

        $group->update($formFields);

        return redirect('/groups/administration')->with('success', 'Your Group has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
