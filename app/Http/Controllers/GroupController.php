<?php

namespace App\Http\Controllers;

use App\Models\Group;

class GroupController extends Controller
{
    public function show(Group $group)
    {
        if ($group->type === 'private' && !$group->isMember(auth()->user())) {
            abort(403);
        }

        return view('groups.show', [
            'group' => $group,
        ]);
    }
}
