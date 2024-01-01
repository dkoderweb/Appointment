<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slot;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentConfirmationMail;
use App\Models\Appointment;

class SlotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $slots = Slot::all();
        return view('admin.index',compact('slots'));
    }
    public function home(){
        $allowedDays = Slot::pluck('day')->toArray();
        return view('welcome',compact('allowedDays'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'day' => 'required',
            'duration' => 'required|max:2',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',  
        ]);
        $slot = new Slot;
        $slot->user_id = auth()->user()->id;
        $slot->day = $request->day;
        $slot->duration = $request->duration;
        $slot->start_time = $request->start_time;
        $slot->end_time = $request->end_time;
        $slot->save();
        return redirect('dashboard');
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
    public function edit($id)
    {
        $slot = Slot::find($id);
        return view('admin.edit',compact('slot'));
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
        $request->validate([
            'day' => 'required',
            'duration' => 'required|max:2',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',  
        ]);
        $slot = Slot::find($id);
        $slot->user_id = auth()->user()->id;
        $slot->day = $request->day;
        $slot->duration = $request->duration;
        $slot->start_time = $request->start_time;
        $slot->end_time = $request->end_time;
        $slot->update();
        return redirect('dashboard');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slot $slot)
    {
        $slot->delete();
        return redirect('dashboard');
    }

    public function fetchSlot(Request $request){
        $slot = Slot::where('day',$request->selected_day)->first();
        $startTime = $slot->start_time;
        $endTime = $slot->end_time;
        $slotDuration = $slot->duration; 

        $startDate = Carbon::today()->setTimeFromTimeString($startTime);
        $endDate = Carbon::today()->setTimeFromTimeString($endTime);

        return $timeSlots = $this->generateTimeSlots($startDate, $endDate, $slotDuration);

    }
    private function generateTimeSlots($startDate, $endDate, $duration)
    {
        $timeSlots = [];
        $currentTime = clone $startDate;
        while ($currentTime <= $endDate) {
            $endTimeSlot = clone $currentTime;
            $endTimeSlot->addMinutes($duration);
            $timeSlots[] = [
                'start' => $currentTime->format('H:i'),
                'end' => $endTimeSlot->format('H:i'),
            ];

            $currentTime->addMinutes($duration);
        }

        return $timeSlots;
    }
    public function bookAppointment(Request $request)
    {        
        $selectedDate = Carbon::createFromFormat('d/m/Y', $request->input('selected_date'))->format('Y-m-d');
        $timeRange = $request->input('selected_slot');
    
        $existingBooking = Appointment::where([
            'email' => $request->email,
            'selected_date' => $selectedDate,
            'selected_slot' => $timeRange,
        ])->exists();
    
        if ($existingBooking) {
            return response()->json(['error' => 'You have already booked this time slot.'], 422);
        }
    
        $data = [
            'email' => $request->email,
            'selected_date' => $selectedDate,
            'selected_slot' => $timeRange,
        ];
    
        Appointment::create($data);
    
        Mail::to($request->input('email'))->send(new AppointmentConfirmationMail($data));
    
        return response()->json(['message' => 'Booking successful. Email sent.']);
    }
    


    
}
