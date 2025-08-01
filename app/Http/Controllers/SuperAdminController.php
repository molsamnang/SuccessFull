<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Customer;
use App\Models\Post_;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'super_admin']);
    }

    public function index()
    {
        $postsCount = Post_::count();
        $customersCount = Customer::count();
        $categoriesCount = Category::count();
        $commentsCount = Comment::count();

        return view('superadmin.dashboard', compact('postsCount', 'customersCount', 'categoriesCount', 'commentsCount'));
    }
}
