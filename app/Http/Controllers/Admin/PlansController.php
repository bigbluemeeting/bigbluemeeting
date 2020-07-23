<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Plan;
use Illuminate\Http\Request;

class PlansController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $plans = Plan::paginate(10);
            $pageName ='Plans List';
            return view('admin.plans.index',compact('plans','pageName'));
        }catch (\Exception $exception)
        {
            return view('errors.500')->with(['danger'=>$exception->getMessage()]);
        }


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        try{
            $billing_frequency_interval = [
                'week' => 'Week',
                'month' =>'Month',
                'year' =>'Year'
            ];

            $plan_type = [
                'shared' =>'Shared',
                'dedicated' =>'Dedicated',
                'cluster' =>'   Cluster'
            ];
            $enabled = [
                'yes' =>'Yes',
                'no' =>'No'
            ];


            $pageName = 'Add Plans';
            return view('admin.plans.create',compact('pageName','billing_frequency_interval','plan_type','enabled'));

        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try{
            $rules = [
                'title' =>'required|unique:plans',
                'participants_total' => 'required|regex:/^[1-9]+/',
                'participants_per_meeting' =>'required|regex:/^[1-9]+/',
                'moderators_per_meeting' =>'required|regex:/^[1-9]+/',
                'webcams' =>'required|regex:/^[1-9]+/',
                'billing_frequency_interval' =>'required|in:week,month,year',
                'billing_frequency_interval_count' =>'required|regex:/^[1-9]+/',
                'minutes' => 'required|regex:/^[1-9]+/',
                'strip_plan_id' =>'required',
                'plan_type' => 'required|in:shared,dedicated,cluster',
                'enable' =>'in:yes,no',
                'recording_Size' =>'required|regex:/^[1-9]+/'

            ];
            $massage = [
                'title.required' =>'Title Field Required',
                'participants_total.required' =>'Participants Total Filed Required',
                'participants_total.regex'=> 'Must Be Positive Numbers & Greater Than 0',
                'participants_per_meeting.required' => 'Participants Per Meeting Filed Required',
                'participants_per_meeting.regex' =>'Must Be Positive Numbers & Greater Than 0',
                'webcams.required' => 'Web Cams Filed Required',
                'webcams.regex' => 'Must Be Positive Numbers & Greater Than 0',
                'billing_frequency_interval.required' => 'Billing Frequency Interval Filed Required',
                'billing_frequency_interval.in' => 'Billing Frequency Interval Value Must In week,month,year',
                'billing_frequency_interval_count.required' => 'Billing Frequency Interval Count Filed Required',
                'billing_frequency_interval_count.regex' => 'Must Be Positive Numbers & Greater Than 0',
                'minutes.required' => 'Minutes Filed Required',
                'minutes.regex' => 'Must Be Positive Numbers & Greater Than 0',
                'strip_plan_id.required' => 'Strip Plan Id Filed Required',
                'plan_type.required' => 'Plan Type Filed Required',
                'plan_type.in' => 'Plan Type Value Must In shared,dedicated,cluster',
                'enable.in' => 'Enable Value Must In yes,no',
                'recording_Size.required' => 'Recording Size Filed Required',
                'recording_Size.regex' =>'Must Be Positive Numbers & Greater Than 0'
            ];
            $this->validate($request,$rules,$massage);
            $data = $request->all();
            $data['enable'] = $data['enable'] == 'yes' ? 1 :0;

            Plan::create($data);

            return redirect()->to(route('plans.create'))->with(['success'=>'Plans Created Successfully']);


        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Plan $plan)
    {
        //
        try{
            $billing_frequency_interval = [
                'week' => 'Week',
                'month' =>'Month',
                'year' =>'Year'
            ];

            $plan_type = [
                'shared' =>'Shared',
                'dedicated' =>'Dedicated',
                'cluster' =>'   Cluster'
            ];
            $enabled = [
                'yes' =>'Yes',
                'no' =>'No'
            ];


            $pageName = 'Edit Plans';
            return view('admin.plans.edit',compact('pageName','plan','billing_frequency_interval','plan_type','enabled'));

        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plan $plan)
    {
        try{
            $rules = [
                'title' =>'required|unique:plans',
                'participants_total' => 'required|regex:/^[1-9]+/',
                'participants_per_meeting' =>'required|regex:/^[1-9]+/',
                'moderators_per_meeting' =>'required|regex:/^[1-9]+/',
                'webcams' =>'required|regex:/^[1-9]+/',
                'billing_frequency_interval' =>'required|in:week,month,year',
                'billing_frequency_interval_count' =>'required|regex:/^[1-9]+/',
                'minutes' => 'required|regex:/^[1-9]+/',
                'strip_plan_id' =>'required',
                'plan_type' => 'required|in:shared,dedicated,cluster',
                'enable' =>'in:yes,no',
                'recording_Size' =>'required|regex:/^[1-9]+/'

            ];
            $massage = [
                'title.required' =>'Title Field Required',
                'participants_total.required' =>'Participants Total Filed Required',
                'participants_total.regex'=> 'Must Be Positive Numbers & Greater Than 0',
                'participants_per_meeting.required' => 'Participants Per Meeting Filed Required',
                'participants_per_meeting.regex' =>'Must Be Positive Numbers & Greater Than 0',
                'webcams.required' => 'Web Cams Filed Required',
                'webcams.regex' => 'Must Be Positive Numbers & Greater Than 0',
                'billing_frequency_interval.required' => 'Billing Frequency Interval Filed Required',
                'billing_frequency_interval.in' => 'Billing Frequency Interval Value Must In week,month,year',
                'billing_frequency_interval_count.required' => 'Billing Frequency Interval Count Filed Required',
                'billing_frequency_interval_count.regex' => 'Must Be Positive Numbers & Greater Than 0',
                'minutes.required' => 'Minutes Filed Required',
                'minutes.regex' => 'Must Be Positive Numbers & Greater Than 0',
                'strip_plan_id.required' => 'Strip Plan Id Filed Required',
                'plan_type.required' => 'Plan Type Filed Required',
                'plan_type.in' => 'Plan Type Value Must In shared,dedicated,cluster',
                'enable.in' => 'Enable Value Must In yes,no',
                'recording_Size.required' => 'Recording Size Filed Required',
                'recording_Size.regex' =>'Must Be Positive Numbers & Greater Than 0'
            ];
            $this->validate($request,$rules,$massage);
            $data = $request->all();
            $data['enable'] = $data['enable'] == 'yes' ? 1 :0;

            $plan->update($data);

            return redirect()->to(route('plans.index'))->with(['success'=>'Plans Updated Successfully']);

        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plan $plan)
    {

        try{
            $plan->delete();
            return redirect()->to(route('plans.index'))->with(['danger'=>'Plans Deleted Successfully']);

        }catch (\Exception $exception){
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }

    }
}
