@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">  
            <form action="{{route('slot.store')}}" method="post">
                @csrf
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Day</label>
                        <select class="form-select"  name="day" >
                            <option value="">Select Day</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option> 
                            <option value="Wednesday">Wednesday</option> 
                            <option value="Thursday">Thursday</option> 
                            <option value="Friday">Friday</option>  
                        </select>
                        @error('day')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Duration</label>
                        <input type="number" name="duration" class="form-control" >
                        @error('duration')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Start Time</label>
                        <input type="time" name="start_time" class="form-control" >
                        @error('start_time')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">End Time</label>
                        <input type="time" name="end_time" class="form-control" >
                        @error('end_time')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>  
                <button type="submit" class="btn btn-primary">Submit</button>
              </form>
        </div>
    </div>
</div>
@endsection
