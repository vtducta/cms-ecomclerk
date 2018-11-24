<?php namespace App\Modules\Products;

use App\Modules\Setting;
use Optimait\Laravel\Traits\CreatedUpdatedTrait;

class ProductHistory extends \Eloquent
{
    use CreatedUpdatedTrait;
    protected $table = 'products_history';
    protected $fillable = ['product_id', 'title', 'cost', 'amazon_title', 'brand', 'asin',
        'amazon_upc_ean', 'upc_ean',
        'amazon_buy_box_price', 'net_after_fba', 'pack_cost', 'number_of_packs',
        'gross_profit_fba', 'gross_roi', 'is_eligible_for_prime', 'profit'];
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    public static $statusLabel = [self::STATUS_ACTIVE => '<label class="label label-success">ACTIVE</label>',
        self::STATUS_INACTIVE => '<label class="label label-danger">DISABLED</label>',];

    public function calculate()
    {
        $str = trim(strtolower(str_replace(['(', ')'], '', $this->amazon_title)));
        $patterns = ['/packs? of ([0-9]+)/i', '/(\d+)?.*?(pack|packs|pk|packets)/i', '/^.*?(\d+)[\s]?[\-]?(pack|packs|pk|packets)/i'];
        $this->number_of_packs = 1;
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $str, $matches)) {
                if (isset($matches[1]) && is_numeric($matches[1]) && $matches[1] > 0) {
                    $this->number_of_packs = $matches[1];
                    break;
                }
            }
        }        /*$this->pack_cost = $this->number_of_packs * $this->cost;        $this->gross_profit_fba =  $this->net_after_fba - $this->pack_cost;        $this->gross_roi  = @(($this->gross_profit_fba / $this->pack_cost) * 100);*/
        $this->save();
        return $this;
    }

    public function selfDestruct()
    {
        return $this->delete();

    }
}