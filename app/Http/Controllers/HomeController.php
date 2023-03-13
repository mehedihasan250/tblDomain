<?php

namespace App\Http\Controllers;

use App\Models\TblDomainData;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Response;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) {

            return view('home');
        }
        
        $allData = TblDomainData::select('list_id',  DB::raw('count(*) as total'))
            ->groupBy('list_id')
            ->pluck('total','list_id')
            ->toArray();
        // dd($allData);
        $data = [];
        foreach($allData as $key=>$item){
            // dd($key);
            $totalStatus =  TblDomainData::where('process_status', 1)->where('list_id', $key)->count();
            $obj = (object)['list_id'=>$key, 'total'=> $item, 'totalStatus' => $totalStatus ];
            $data[] = $obj;
        }

        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($row){

               $btn = '<a href="'. route('list-index', $row->list_id) .'" class="edit btn btn-primary btn-sm">View</a>';

                return $btn;
        })
        ->rawColumns(['action'])
        ->make(true);
        
    }

    public function insertDomain(Request $request)
    {
        if($request->domains == '' || $request->domains == null){
            return [
                'status' => 'error',
                'html' => 'Domain is required.'
            ];
        }

        $data = explode(',' ,$request->domains);

        $insertData = [];
        $max = TblDomainData::max('list_id');
        if($max==0){
            $max = 1000;
        }else{
            $max = $max+1;
        }

        $invalid = 0;
        foreach($data as $item){
            $item = trim($item);

            if (filter_var($item, FILTER_VALIDATE_URL)) {
                $item = str_replace("https://", "",$item);
                $item = str_replace("http://", "",$item);
            }
            $item = str_replace("www.", "",$item);

            $res = preg_match('/^(?:[-A-Za-z0-9]+\.)+[A-Za-z]{2,6}$/', $item);
            if(!$res){
                $invalid = $invalid + 1;
            }
            array_push($insertData, ['list_id'=> $max, 'domain_name'=> $item]);
        }

        if($invalid != 0){
            return [
                'status' => 'error',
                'html' => 'You have '. $invalid .' invalid domain.'
            ];
        }
        $response = TblDomainData::insert($insertData);
        
        return [
            'html' => 'Domain insert successfuly.',
            'status' => 'success'
        ];
    }

    public function listIndex(Request $request, $listId)
    {
        if (!$request->ajax()) {
            $data['listId'] = $listId;
            return view('list-index', $data);
        }
        
        $data = TblDomainData::where('list_id', $listId)->get();

        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('dmarc_flag_status', function($row){
            return $row->dmarc_flag == 0?'<div class="text-danger">Not found</div>':'<div class="text-success">Found</div>';
        })
        ->addColumn('dkim_flag_status', function($row){
            return $row->dkim_flag == 0?'<div class="text-danger">Not found</div>':'<div class="text-success">Found</div>';
        })
        ->addColumn('process_status', function($row){
            return $row->process_status == 0?'<div class="text-danger">Not Process</div>':'<div class="text-success">Processed</div>';
        })
        ->rawColumns(['dmarc_flag_status', 'dkim_flag_status', 'process_status'])
        ->make(true);
        
    }

    public function profile()
    {
        return view('profile');
    }

    public function passwordReset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'old_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:6', 'same:password_confirmation'],
        ]);

        if ($validator->fails()) {
            return Response::json(['status' => 'validation-error', 'html' => $validator->errors()]);
            
        }

        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
        return [
            'html' => 'Password updated successfuly.',
            'status' => 'success'
        ];


    }
}
