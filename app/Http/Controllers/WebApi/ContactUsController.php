<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ContactDetailsRequest;
use App\Models\Country;
use App\Repositories\ContactDetailsRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactUsController extends BaseController
{

    private $contact_repo;

    public function __construct(ContactDetailsRepository $contact_repo)
    {
        parent::__construct();
        $this->contact_repo = $contact_repo;
    }

    public function getAllCountry()
    {
        try {
            $country = Country::all();
            return $this->sendSuccess($country);
        } catch (Exception $e) {
            Log::info($e);
            return $this->sendError("Something went wrong please try again!");
        }
    }
    public function addContactDetails(ContactDetailsRequest $request)
    {
        $data = $request->all();
        try {
            $this->contact_repo->dataCrud($data);
            return $this->sendSuccess('', 'Contact Form Add Successfully');
        } catch (\Exception $e) {
            return $this->sendException($e);
        }
    }
}
