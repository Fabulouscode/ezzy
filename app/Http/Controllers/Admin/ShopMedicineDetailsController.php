<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\MedicineDetailsRepository;
use App\Repositories\MedicineCategoryRepository;
use App\Repositories\MedicineSubcategoryRepository;
use App\Repositories\MedicineImagesRepository;
use App\Repositories\ShopMedicineDetailsRepository;

class ShopMedicineDetailsController extends Controller
{
    private $medicine_details_repo, $medicine_subcategory_repo, $medicine_category_repo, $medicine_images_repo, $shop_medicine_repo;

    public function __construct(
        MedicineDetailsRepository $medicine_details_repo,
        MedicineSubcategoryRepository $medicine_subcategory_repo, 
        MedicineCategoryRepository $medicine_category_repo,
        MedicineImagesRepository $medicine_images_repo,
        ShopMedicineDetailsRepository $shop_medicine_repo
        )
    {
        $this->medicine_details_repo = $medicine_details_repo;
        $this->medicine_subcategory_repo = $medicine_subcategory_repo;
        $this->medicine_category_repo = $medicine_category_repo;
        $this->medicine_images_repo = $medicine_images_repo;
        $this->shop_medicine_repo = $shop_medicine_repo;
    }
}
