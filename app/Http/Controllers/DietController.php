<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

use App\Http\Requests\PatientDietRequest;

use Auth;
use DB;
use Storage;
use Mail;
use App\Models\Patient;
use App\Models\Diet;
use App\Models\User;

use App\Support\SMS;

class DietController extends Controller
{
	public function __construct(Request $request)
    {
    
        $this->nutritionist = isset($request->user) ? $request->user : Auth::user()->employee->name;
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date("Y/m/d 0:0:0");
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y/m/d 23:59:59');
        
    }

	public function diets()
	{
		//Update patient_id in Diet Assign
        //DB::update("UPDATE diet_assign AS d SET patient_id = (SELECT id FROM patient_details AS p WHERE d.clinic=p.clinic AND d.registration_no=p.registration_no) WHERE patient_id = 0");

        //Update nutritionist in Diet Assign
        //DB::update("UPDATE diet_assign AS d SET nutritionist = (SELECT nutritionist FROM patient_details AS p WHERE d.clinic=p.clinic AND d.registration_no=p.registration_no) WHERE date_assign >='2015-09-01'");

	    $diets = Diet::getDiets($this->start_date, $this->end_date, $this->nutritionist);

        $users = User::getUsersByRole('nutritionist');

        //dd($diets);
        $data = array(
            'menu'          => 'nutritionist',
            'section'       => 'diets',
            'diets'         =>  $diets,
            'users'         =>  $users,
            'name'          =>  $this->nutritionist,
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'i'             =>  '0'
        );

        return view('home')->with($data);
    }

    public function destroy(Request $request)
    {
        if(Diet::destroy($request->id))
        {
            return "Diet deleted successfully";
        }

        return "Error";

    }

    public function store(PatientDietRequest $request, $id)
    {
        $date = date('Y-m-d', strtotime($request->date));

        $diet = Diet::where('patient_id', $id)
                    ->where('date_assign', $date)
                    ->first();
        if($diet)
        {
            $data = array(
                'message'   => 'Diet already added for '. $date,
                'status'    => 'error'
            );

            return redirect('patient/'.$id.'/diet')->with($data);
        }


        $patient = Patient::find($id);

        $diet = new Diet;


        $diet->patient_id = $id;
        //$diet->clinic = $patient->clinic;
        //$diet->registration_no = $patient->registration_no;
        $diet->nutritionist = Auth::user()->employee->name;

        $diet->date = date('Y-m-d h:i:s');
        $diet->date_assign = date('Y-m-d', strtotime($request->date));
        $diet->weight = trim($request->weight);

        //$diet->early_morning = trim($request->early_morning);
        $diet->breakfast = trim($request->breakfast);
        $diet->mid_morning = trim($request->mid_morning);
        $diet->lunch = trim($request->lunch);
        $diet->evening = trim($request->evening);
        $diet->dinner = trim($request->dinner);
        $diet->herbs = trim($request->herbs);
        $diet->rem_dev = trim($request->rem_dev);
        $diet->save();

        $data = array(
                'message'   => 'Diet Added',
                'status'    => 'success'
            );

        return redirect('patient/'.$id.'/diet')->with($data);
    }

    public function send(Request $request, $id)
    {
        $request->patient_id = $id; 

        $status = Diet::send($request);

        $data = array(
                'message'   => $status,
                'status'    => 'success'
            );

        return redirect('patient/'.$id.'/diet')->with($data);
    }

    

    public function edit($id) {

        $diet = Diet::find($id);

        $patient = Patient::where('id', $diet->patient_id)->first();

        $data = array(
            'patient'   =>  $patient,
            'diet'      =>  $diet,
            'id'        =>  $id
        );

        return view('modals.diet')->with($data); 
    }

    public function update(Request $request)
    {

        $diet = Diet::find($request->id);

        //$diet->date = date('Y-m-d', strtotime($request->date));

        $diet->weight = $request->weight;

        //$diet->early_morning = $request->early_morning;
        $diet->breakfast = $request->breakfast;
        $diet->mid_morning = $request->mid_morning;
        $diet->lunch = $request->lunch;
        $diet->evening = $request->evening;
        $diet->dinner = $request->dinner;
        $diet->herbs = $request->herbs;
        $diet->rem_dev = $request->rem_dev;
        $diet->save();

        return "Diet updated!";
    }


    public function autocomplete(Request $request)
    {
        $diets = null;
        //if(count($request->term) > 3) {

        $diets = Diet::select(DB::RAW($request->id.' as diet' ))
                    ->where($request->id, 'like', '%'.$request->term.'%')
                    ->where('nutritionist', 'like', Auth::user()->employee->name)
                    ->limit(10)
                    ->orderBy('id', 'desc')
                    ->groupBy(DB::RAW($request->id))
                    ->get();
        //}
        return $diets;
    }

    /**WIP Saaz Rai 30-01-2016**/
    public function dietNotStarted()
    {
        

        $patients2 = Patient::select("patient_details.*")
                    ->join(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                        $join->on('patient_details.id', '=', 'f.patient_id');
                    })
                    ->leftJoin(DB::raw('(SELECT patient_id, date_assign FROM diet_assign A WHERE date_assign = (SELECT MAX(date_assign) FROM diet_assign B WHERE A.patient_id=B.patient_id)) AS d'), function($join) {
                        $join->on('patient_details.id', '=', 'd.patient_id');
                    })
                    ->where('end_date', '>=', date('Y-m-d'))
                    //->where('date_assign', '<', 'f.start_date')
                    ->whereNull('d.patient_id')
                    ->get();

        dd($patients2);
    }

}