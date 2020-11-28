<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\VoucherCodeRepository;

class VoucherCodeController extends BaseApiController
{
    private $voucher_code_repo;

    public function __construct(VoucherCodeRepository $voucher_code_repo)
    {
        parent::__construct();
        $this->voucher_code_repo = $voucher_code_repo;
    }

    public function getVoucherCodeDetails(Request $request)
    {
        $data = array();
        $data = $this->voucher_code_repo->getVoucherCodeList($request);
        return self::sendSuccess($data, 'Voucher Code List');
    }

    public function getByIdVoucherCodeDetails($id)
    {
        $data = array();
        $data = $this->voucher_code_repo->getbyId($id);
        return self::sendSuccess($data, 'Voucher Code details');
    }

}
