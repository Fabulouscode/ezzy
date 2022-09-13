<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;

class UserWalletController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::whereNull('parent_id')->get();
        $users = User::where('status', 1)->get();
        return view('admin.user_wallet', compact('users', 'categories'));
    }

    public function getCategoryUser(Request $request)
    {
        if ($request->category_id == 0) {
            $user = User::whereNull('category_id')->whereNull('subcategory_id')->where('status',0)->get();
        } else {
            $category = Category::where('parent_id', $request->category_id)->pluck('id')->toArray();
            $user = User::whereIn('category_id', $category)->where('status',0)->get();
        }

        return response()->json(['status' => true, 'data' => $user], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'category_id' => 'required',
            'user_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->WithInput();
        } else {
            dd($request->all());
        }
    }
}
