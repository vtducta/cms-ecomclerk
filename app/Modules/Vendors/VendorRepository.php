<?php

namespace App\Modules\Vendors;

use App\Vendor;
use Auth;
use Hash;
use Optimait\Laravel\Exceptions\ApplicationException;
use Optimait\Laravel\Repos\EloquentRepository;
use Optimait\Laravel\Traits\UploaderTrait;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/*
 * Class UserRepository
 * @package App\Admin\User
 */

class VendorRepository extends EloquentRepository
{
    use UploaderTrait;
    /*
     * @var UserValidator
     */
    public $validator;

    /*
     * @var
     */
    protected $insertedId;


    /*
     * @param User $user
     * @param UserValidator $userValidator
     */
    public function __construct(Vendor $vendor, VendorValidator $vendorValidator)
    {
        $this->model = $vendor;
        $this->validator = $vendorValidator;
    }

    /**
     * @param array $searchData
     * @param int $items
     * @param string $orderBy
     * @param string $orderType
     * @return Vendor[]|\Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function getPaginated($searchData = [], $items = 10, $orderBy = 'title', $orderType = 'DESC')
    {
        $model = $this->model->where(function ($q) use ($searchData) {

        })->orderBy($orderBy, $orderType);
        if (!is_null($items)) {
            return $model->paginate($items);
        }
        return $model->get();
    }

    /**
     * @param int $items
     * @param string $orderBy
     * @param string $orderType
     * @return mixed
     */
    public function getClientsPaginated($items = 10, $orderBy = 'name', $orderType = 'DESC')
    {
        return $this->model
            ->clients()
            ->orderBy($orderBy, $orderType)
            ->paginate($items);
    }


    /**
     * @param $data
     * @param $param
     * @return mixed
     */
    public function importVendor($data, $param)
    {
//            dd($param);
            foreach (array_chunk($data, 5000) as $t) {
                if($param == 'insert') {
                    $this->model->insert($t);
                }
                else{
                    $table = 'vendors';
                    $first = reset($t);
                    $columns = implode( ',',
                        array_map( function( $value ) { return "$value"; } , array_keys($first) )
                    );
                    $values = implode( ',', array_map( function( $row ) {
                            return '('.implode( ',',
                                    array_map( function( $value ) { return '"'.str_replace('"', '""', $value).'"'; } , $row )
                                ).')';
                        } , $t )
                    );

                    $updates = implode( ',',
                        array_map( function( $value ) { return "$value = VALUES($value)"; } , array_keys($first) )
                    );

                    $sql = "INSERT INTO {$table}({$columns}) VALUES {$values} ON DUPLICATE KEY UPDATE {$updates}";

                    return \DB::statement( $sql );
                }
            }
//        $vendor = $this->model->Create([]);
//        $vendor->title = isset($data['title']) ? $data['title'] : NULL;
//        $vendor->minimum_purchase_amount = isset($data['minimum_purchase_amount']) ? $data['minimum_purchase_amount'] : NULL;
//        $vendor->minimum_weight_amount = isset($data['minimum_weight_amount']) ? $data['minimum_weight_amount'] : NULL;
//        $vendor->minimum_case_quantity = isset($data['minimum_case_quantity']) ? $data['minimum_case_quantity'] : NULL;
//        $vendor->save();
    }

    /**
     * @return bool|null
     * @throws \Exception
     */
    public function deleteAll()
    {
        return $this->model->where('id', '>', 0)->delete();
    }

}