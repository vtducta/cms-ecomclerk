<?php

namespace App\Modules\ProductFBA;

use App\ProductFBA;
use App\VendorProduct;
use Optimait\Laravel\Repos\EloquentRepository;
use Optimait\Laravel\Traits\UploaderTrait;
use DB;
/*
 * Class UserRepository
 * @package App\Admin\User
 */

class ProductFbaRepository extends EloquentRepository
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
    public function __construct(ProductFBA $product_fba, ProductFbaValidator $product_fbaValidator)
    {
        $this->model = $product_fba;
        $this->validator = $product_fbaValidator;
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
     * @param array $searchData
     * @param int $items
     * @param string $orderBy
     * @param string $orderType
     * @param $vendor_id
     * @return mixed
     */
    public function getPurchaseOrderPaginated($searchData = [], $items = 10, $orderBy = 'vendor_products.title', $orderType = 'DESC',$vendor_id)
    {
        $productfbaList = DB::table('product_fba')
            ->select('*')
            ->join('vendor_products', 'product_fba.upc', '=', 'vendor_products.upc')
            ->join('vendors', 'vendor_products.vendor_id', '=', 'vendors.id')
            ->where('vendor_products.vendor_id',$vendor_id)
            ->orderBy('product_fba.'.$orderBy, $orderType)
            ->get();

        if($productfbaList)
            return $productfbaList;
    }

    /**
     * getClientsPaginated
     * @param mixed $items
     * @param mixed $orderBy
     * @param mixed $orderType
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
     * import
     * @param mixed $data
     * @return mixed
     */
    public function import($data, $param)
    {
        foreach (array_chunk($data, 5000) as $t) {
            if($param == 'insert') {
                $this->model->insert($t);
            }
            else{
                $table = 'product_fba';
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
//        $product_fba = $this->model->Create([]);
//        $product_fba->title = isset($data['title']) ? $data['title'] : null;
//        $product_fba->buy_box = isset($data['buy_box']) ? $data['buy_box'] : null;
//        $product_fba->asin = isset($data['asin']) ? $data['asin'] : null;
//        $product_fba->upc = isset($data['upc']) ? $data['upc'] : null;
//        $product_fba->profit = isset($data['profit']) ? $data['profit'] : null;
//        $product_fba->estimated_monthly_sales = isset($data['estimated_monthly_sales']) ? $data['estimated_monthly_sales'] : null;
//        $product_fba->save();

    }
    public function deleteAll()
    {
        return $this->model->where('id', '>', 0)->delete();
    }

}
