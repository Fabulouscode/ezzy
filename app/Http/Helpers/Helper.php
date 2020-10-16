<?php 

namespace App\Http\Helpers;

use App\Repositories\CategoryRepository;
use App\Models\Category;

class Helper
{
    private $category_repo;

    public function __construct(CategoryRepository $category_repo)
    {
        $this->category_repo = $category_repo;
    }

    public static function getCategoryName($id)
    {
        $category_name = '';
        // $categories = $this->category_repo->get();
        $categories = Category::get();
        foreach ($categories as $key => $value) {
            if($value->id == $id){
                $category_name = $value->name;
                break;
            }
        }
        return $category_name;
    }

    
}