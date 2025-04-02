<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Integration;
use Illuminate\Validation\Rule;
use Keygen;
use Auth;
use DB;
use App\Traits\CacheForget;

class IntegrationController extends Controller
{
    use CacheForget;
    public function index()
    {
        $lims_integration_all = Integration::where('is_active', true)->get();
        $numberOfIntegration = Integration::where('is_active', true)->count();
        return view('backend.integration.create', compact('lims_integration_all', 'numberOfIntegration'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => [
                'max:255',
                    Rule::unique('integrations')->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ],
        ]);
        $input = $request->all();
        $input['is_active'] = true;
        Integration::create($input);
        $this->cacheForget('integration_list');
        return redirect('integration')->with('message', 'Data inserted successfully');
    }

    public function edit($id)
    {
        $lims_integration_data = Integration::findOrFail($id);
        return $lims_integration_data;
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => [
                'max:255',
                    Rule::unique('integrations')->ignore($request->integration_id)->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ],
        ]);
        $input = $request->all();
        $lims_integration_data = Integration::find($input['integration_id']);
        $lims_integration_data->update($input);
        $this->cacheForget('integration_list');
        return redirect('integration')->with('message', 'Data updated successfully');
    }

    public function importIntegration(Request $request)
    {
        //get file
        $upload=$request->file('file');
        $ext = pathinfo($upload->getClientOriginalName(), PATHINFO_EXTENSION);
        if($ext != 'csv')
            return redirect()->back()->with('not_permitted', 'Please upload a CSV file');
        $filename =  $upload->getClientOriginalName();
        $upload=$request->file('file');
        $filePath=$upload->getRealPath();
        //open and read
        $file=fopen($filePath, 'r');
        $header= fgetcsv($file);
        $escapedHeader=[];
        //validate
        foreach ($header as $key => $value) {
            $lheader=strtolower($value);
            $escapedItem=preg_replace('/[^a-z]/', '', $lheader);
            array_push($escapedHeader, $escapedItem);
        }
        //looping through othe columns
        while($columns=fgetcsv($file))
        {
            if($columns[0]=="")
                continue;
            foreach ($columns as $key => $value) {
                $value=preg_replace('/\D/','',$value);
            }
           $data= array_combine($escapedHeader, $columns);

           $integration = Integration::firstOrNew([ 'name'=>$data['name'], 'is_active'=>true ]);
           $integration->name = $data['name'];
           $integration->phone = $data['phone'];
           $integration->email = $data['email'];
           $integration->address = $data['address'];
           $integration->is_active = true;
           $integration->save();
        }
        $this->cacheForget('integration_list');
        return redirect('integration')->with('message', 'Integration imported successfully');
    }

    public function deleteBySelection(Request $request)
    {
        $integration_id = $request['integrationIdArray'];
        foreach ($integration_id as $id) {
            $lims_integration_data = Integration::find($id);
            $lims_integration_data->is_active = false;
            $lims_integration_data->save();
        }
        $this->cacheForget('integration_list');
        return 'Integration deleted successfully!';
    }

    public function destroy($id)
    {
        $lims_integration_data = Integration::find($id);
        $lims_integration_data->is_active = false;
        $lims_integration_data->save();
        $this->cacheForget('integration_list');
        return redirect('integration')->with('not_permitted', 'Data deleted successfully');
    }

    public function integrationAll()
    {
        if(Auth::user()->role_id > 2)
            $lims_integration_list = DB::table('integrations')->where([
            ['is_active', true],
            ['id', Auth::user()->integration_id]
        ])->get();
        else
            $lims_integration_list = DB::table('integrations')->where('is_active', true)->get();

        $html = '';
        foreach($lims_integration_list as $integration){
            $html .='<option value="'.$integration->id.'">'.$integration->name.'</option>';
        }

        return response()->json($html);
    }
}
