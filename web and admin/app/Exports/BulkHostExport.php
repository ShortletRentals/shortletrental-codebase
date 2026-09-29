<?php
namespace App\Exports;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;
Use \Carbon\Carbon;

class BulkHostExport implements FromQuery,WithHeadings,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */  
    // use Exportable;

    public function __construct($request)
    {
        $this->file_type = $request->file_type;
        $this->status = $request->status??null;
        $this->start_date = $request->start_date??null;
        $this->end_date = $request->end_date??null;

    }

    public function headings(): array
    {
        return [
            'name',
            'surname',
            'country_code',
            'mobile',
            'Email',
            'dob',
            'gender',
            'marital_status',
            'address',
            'addeded_date',
        ];
    }
    public function query()
    {
        $q = User::query()->select('users.*')->where('user_type', 5);

        
        $orderby = 'users.created_at';
        $order = 'desc';
        
        if(isset($this->status) && in_array($this->status,[0,1,2]))
        {
            $q->where('users.status',$this->status);
        }
        
        if(isset($this->start_date) && isset($this->end_date))
        {
            $q->whereBetween('users.created_at',[$this->start_date.' 00:00:01',$this->end_date.' 23:59:59']);
        }else{
            if(isset($this->start_date) && $this->end_date == null){
                $q->whereDate('users.created_at',$this->start_date);
            }
        }
        $q->orderBy('users.id',"Desc");
        return $q;
    }
    public function map($bulk): array
    {
        return [
            $bulk->name,
            $bulk->surname,
            $bulk->country_code,
            $bulk->mobile,
            $bulk->email,
            date('d M Y', strtotime($bulk->dob)),
            $bulk->gender,
            $bulk->marital_status,
            $bulk->address,
            date('d M Y', strtotime($bulk->created_at)),
        ];
    }

}
