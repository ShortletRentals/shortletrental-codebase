<?php
namespace App\Exports;
use App\Models\Orders;
use App\Models\Property;
use App\Models\Transection;
use App\Models\Country;
use App\Models\Province;
use App\Models\City;
use App\Models\Area;
use App\Models\User;
use App\Models\Discount;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;
Use \Carbon\Carbon;

class BulkDiscountExport implements FromQuery,WithHeadings,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */  
    // use Exportable;

    public function __construct($request)
    {
        // dd($request->all());
        $this->file_type = $request->file_type;
        $this->start_date = $request->start_date??null;
        $this->end_date = $request->end_date??null;
        $this->discount_id = $request->discount_id??null;
        $this->inc = 0;
        // $this->is_product_selected = $_GET['is_product_selected'] ?? null;
    }

    public function headings(): array
    {
        return [
            'S. No.',
            'Customer Name',
            'Discount Code',
            'Amount',
            'Date & Time',
        ];
    }
    public function query()
    {
        $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
        $discountData = Discount::where('id',$this->discount_id)->first();
        // dd($discountData);
        if(isset($user_type) && $user_type == 3){
            $q = DB::table('bookings')->where(['influencer_id'=>auth()->id(), 'coupon_code'=>$discountData->code ]);
        }else{
            $q = DB::table('bookings')->where(['coupon_code'=>$discountData->code ]);
        }

        if(isset($this->start_date) && isset($this->end_date))
        {
            $q->whereBetween('bookings.created_at',[$this->start_date.' 00:00:01',$this->end_date.' 23:59:59']);
        }else{
            if(isset($this->start_date) && $this->end_date == null){
                $q->whereDate('bookings.created_at',$this->start_date);    
            }
        }
        $orderby = 'bookings.created_at';
        $order = 'desc';
        $response = $q->orderBy($orderby, $order);
        // dd($response->get());
        return $response;
    }
    public function map($value): array
    {
        // echo '<pre>'; print_r($value); exit;
        $this->inc = $this->inc+1;
        // dd($value);
        return [
            $this->inc,
            isset($value->personal_last_name)? $value->personal_first_name.' '.$value->personal_last_name:'N/A',
            isset($value->coupon_code)? $value->coupon_code:'N/A',
            isset($value->discount_amount)? $value->discount_amount:'N/A',
            date('d M Y', strtotime($value->created_at)),
        ];
    }

}
