<?php

namespace App\Modules\VendorProducts;

use App\VendorProduct;
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

class VendorProductsRepository extends EloquentRepository
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


    /**
     * VendorProductsRepository constructor.
     * @param VendorProducts $vendorProducts
     * @param VendorProductsValidator $vendorProductsValidator
     */
    public function __construct(VendorProduct $vendorProducts, VendorProductsValidator $vendorProductsValidator)
    {
        $this->model = $vendorProducts;
        $this->validator = $vendorProductsValidator;
    }


    /**
     * @param array $searchData
     * @param int $items
     * @param string $orderBy
     * @param string $orderType
     * @return VendorProduct[]|\Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function getPaginated($searchData = [], $items = 10, $orderBy = 'product_title', $orderType = 'DESC')
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
    public function importVendorProducts($data, $param)
    {
        foreach (array_chunk($data, 5000) as $t) {

            if($param == 'insert') {
                $this->model->insert($t);
            }
            else{
                $table = 'vendor_products';
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
        //        dd($data);
        //        $vendor = $this->model->insert($data);
        //        $vendor->product_title = isset($data['product_title']) ? $data['product_title'] : NULL;
        //        $vendor->vendor_item_number = isset($data['vendor_item_number']) ? $data['vendor_item_number'] : NULL;
        //        $vendor->upc = isset($data['upc']) ? $data['upc'] : NULL;
        //        $vendor->vendor_cost = isset($data['vendor_cost']) ? $data['vendor_cost'] : NULL;
        //        $vendor->case_quantity = isset($data['case_quantity']) ? $data['case_quantity'] : NULL;
        //        $vendor->weight = isset($data['weight']) ? $data['weight'] : NULL;
        //        $vendor->category = isset($data['category']) ? $data['category'] : NULL;
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