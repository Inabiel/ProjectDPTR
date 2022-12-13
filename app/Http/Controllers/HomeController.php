<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use App\Models\dpemanfaatan;
use App\Models\pengawasan;
use App\Models\FileUpload;
use Illuminate\Support\Facades\DB;
  
class HomeController extends Controller
{
    public function index()
    {
        $dtpemanfaatan = dpemanfaatan::with('files')->get();
        $dtpengawasan = DB::table('pengawasan')->get();
        
        $jml_pemanfaatan = dpemanfaatan::count();
        $jml_pengawasan = pengawasan::count();
        
       return view('home', ['pengawasan' => $jml_pengawasan, 'pemanfaatan' => $jml_pemanfaatan], compact('dtpengawasan','dtpemanfaatan',));

        $jml_pemanfaatan = dpemanfaatan::count();
        $jml_pengawasan = pengawasan::count();
        return view('home',  ['pengawasan' => $jml_pengawasan, 'pemanfaatan' => $jml_pemanfaatan ]);

        $result =DB::select(DB::raw("SELECT COUNT(*) as total_kabupaten, kabupaten FROM 'pemanfaatan' group by kabupaten;"));
        $chartData="";
        foreach($result as $list){
            $chartData.="['".$list->kabupaten."', ".$list->total_kabupaten."],";
        }
        $arr['chartData']=rtrim($chartData,",");
        return view('home', $arr);
    }
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

//     public function tabeldata()
//     {
//         $dtpemanfaatan = DB::table('pemanfaatan')
//         ->join('file', 'file.id_pemanfaatan', '=', 'pemanfaatan.id')
        
//         ->get();

// return view('user_dashboard', compact('dtpemanfaatan'));

// $dtpengawasan = DB::table('pengawasan');

// return view('user_dashboard', compact('dtpengawasan'));
        
//     }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $files = [];
        if($request->hasfile('filenames'))
        {
            foreach($request->file('filenames') as $file)
            {
                $name = time().rand(1,100).'.'.$file->extension();
                $file->move(public_path('files'), $name);  
                $files[] = $name;  
            }
        }
        // dd($request->all());
        $hello = dpemanfaatan::create([
    
            'kode_perizinan'=> $request->kode_perizinan,
            'desa_kecamatan'=>$request->desa_kecamatan,
            'kabupaten'=>$request->kabupaten,
            'kelurahan'=>$request->kelurahan,
            'persil'=>$request->persil,
            'luas'=>$request->luas,
            'uraian'=>$request->uraian,
            'tanggal_mulai'=>$request->tanggal_mulai,
            'tanggal_akhir'=>$request->tanggal_akhir,
            'file_SK' => 'hehe :P'
        ]);

        $hello = pengawasan::create([
            'id'=>$request->id,
            'kabupaten'=>$request->kabupaten,
            'kapanewon'=>$request->kapanewon,
            'kelurahan'=>$request->kelurahan,
            'tahun_pengawasan'=>$request->tahun_pengawasan,
            'nomor_sk'=>$request->nomor_sk,
            'tanggal_sk'=>$request->tanggal_sk,
            'bentuk_pemanfaatan'=>$request->bentuk_pemanfaatan,
            'pengelola'=>$request->pengelola,
        ]);
        

        // dd($hello);

        foreach($files as $file) {
            FileUpload::create([
                'filename' => $file,
                'id_pemanfaatan' => $hello->id
            ]);
        }

        //     $nm = request()->file('file_SK');
        //     $namaFile = $nm->store("img/menu");

        //     $dtUpload = new Uploadgambar;
        //     $dtUpload->file_SK =$namaFile;

        //     $nm->move(public_path().'/img', $namaFile);
        //     $dtUpload->save();

        // ]);




        return redirect('home');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(pengawasan::with('pengawasan')->find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}