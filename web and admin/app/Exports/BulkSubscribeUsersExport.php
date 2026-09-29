<?php
namespace App\Exports;
use App\Models\User;
use App\Models\Subscription;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;
Use \Carbon\Carbon;

class BulkSubscribeUsersExport implements FromQuery,WithHeadings,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */  
    // use Exportable;

    public function __construct($request)
    {
        // dd($request->all());
        $this->file_type = $request->file_type;
    }

    public function headings(): array
    {
        return [
            'Subscribe Type',
            'Email',
            'Added Date',
            // 'Created-At',
        ];
    }
    public function query()
    {
        // dd(auth()->id());
        $Product = Subscription::where('status',1);
        // dd($Product);
        return $Product;
        /*you can use condition in query to get required result
         return Bulk::query()->whereRaw('id > 5');*/
    }
    public function map($bulk): array
    {
        // dd($bulk);
        return [
            $bulk->type,
            $bulk->email,
            // $bulk->type,
            date('d M Y', strtotime($bulk->created_at)),
        ];
    }

}
