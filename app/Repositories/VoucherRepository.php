<?php

namespace App\Repositories;

use App\Models\Voucher;
use Exception;
use App\Exceptions\AppCustomException;

class VoucherRepository extends Repository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.VoucherRepository');
    }

    /**
     * Return trucks.
     */
    public function getVouchers(
        $whereParams=[],
        $orWhereParams=[],
        $relationalOrParams=[],
        $orderBy=['by' => 'id', 'order' => 'asc', 'num' => null],
        $aggregates=['key' => null, 'value' => null],
        $withParams=[],
        $activeFlag=true
    ){
        $vouchers = [];

        try {
            $vouchers = empty($withParams) ? Voucher::query() : Voucher::with($withParams);

            $vouchers = $activeFlag ? $vouchers->active() : $vouchers;

            $vouchers = parent::whereFilter($vouchers, $whereParams);

            $vouchers = parent::orWhereFilter($vouchers, $orWhereParams);

            $vouchers = parent::relationalOrFilter($vouchers, $relationalOrParams);

            return (!empty($aggregates['key']) ? parent::aggregatesSwitch($vouchers, $aggregates) : parent::getFilter($vouchers, $orderBy));
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 1);

            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $vouchers;
    }

    /**
     * Return trucks.
     */
    public function getVoucher($id, $withParams=[], $activeFlag=true)
    {
        $voucher = [];

        try {
            if(empty($withParams)) {
                $voucher = Voucher::query();
            } else {
                $voucher = Voucher::with($withParams);
            }

            if($activeFlag) {
                $voucher = $voucher->active();
            }

            $voucher = $voucher->findOrFail($id);
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 4);

            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $voucher;
    }

    /**
     * Save voucher.
     */
    public function saveVoucher($inputArray=[], $id=null)
    {
        try {
            //find record with id or create new if none exist
            $voucher = Voucher::findOrNew($id);

            foreach ($inputArray as $key => $value) {
                $voucher->$key = $value;
            }
            //voucher save
            $voucher->save();

            return [
                'flag'    => true,
                'voucher' => $voucher,
            ];
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 3);

            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return [
            'flag'      => false,
            'errorCode' => $this->repositoryCode + 3,
        ];
    }

    /**
     * delete voucher.
     */
    public function deleteVoucher($id, $forceFlag=false)
    {
        try {
            //get voucher
            $voucher = $this->getVoucher($id, [], false);

            //force delete or soft delete
            //related models will be deleted by deleting event handlers
            $forceFlag ? $voucher->forceDelete() : $voucher->delete();

            return [
                'flag'  => true,
                'force' => $forceFlag,
            ];
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ?  $e->getCode() : $this->repositoryCode + 5);
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return [
            'flag'          => false,
            'errorCode'    => $this->repositoryCode + 6,
        ];
    }
}
