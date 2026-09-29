<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\FacilityOwnerExport;
use App\Models\CronLiveData;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;

class ItlyController extends Controller
{
    public function __construct() {
        $this->Models = new CronLiveData();

    }

    function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }

    public function importData(){

        $file = '/var/www/html/products_export_1.csv';
        $customerArr = $this->csvToArray($file);

        foreach($customerArr as $row)
        {
            $update =  MasterProduct::where('sku',$row['Variant SKU'])->where('main_category_id',7)->update(['cost_price'=>$row['Cost per item'],'price'=>$row['Variant Price']]);
        }
        dd($customerArr);

/*        ini_set('memory_limit', '-1');
        ini_set('display_startup_errors', 1);
        ini_set('display_errors', 1);
        error_reporting(-1);


        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.brandsdistribution.com/restful/export/api/products.xml?acceptedlocales=en_US",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
            "accept: application/xml",
            "authorization: Basic ODdhZjViNzMtYTk5NC00ZWUzLWEyYTctYTZiMTQ1MDhhMDJhOkR1YmFpMjAyMiE=",
            "cache-control: no-cache"
            ),
        ));
        $response = curl_exec($curl);
        
        $err = curl_error($curl);

        curl_close($curl);
        $xml = simplexml_load_string($response);
        $json = json_encode($xml);

        $array = json_decode($json);


    
        if (isset($array->items->item) && !empty($array->items->item)) {
            foreach ($array->items->item as $key => $value) {            
                if (is_object($value->models->model)) {
                    MasterProduct::where('barcode',$value->models->model->barcode)->update([
                        'cost_price'=>$value->models->model->minPrice,
                        'price'=>$value->models->model->streetPrice,
                        'discount_price'=>$value->models->model->suggestedPrice,
                        'taxable'=>$value->models->model->taxable]);
                }
                else
                {
                    foreach($value->models->model as $pro)
                    {
                        MasterProduct::where('barcode',$pro->barcode)->update([
                            'cost_price'=>$pro->minPrice,
                            'price'=>$pro->streetPrice,
                            'discount_price'=>$pro->suggestedPrice,
                            'taxable'=>$pro->taxable]); 
                    }
                }
            }


        }
  */
    }

}
