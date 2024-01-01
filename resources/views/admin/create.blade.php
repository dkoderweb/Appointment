@extends('layouts.app')

@section('content') 
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">  
            <form action="{{ route('slot.store') }}" method="post" id="slotForm">
                @csrf
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Day</label>
                        <select class="form-select" name="day">
                            <option value="">Select Day</option>
                            <option value="Monday" {{ old('day') == 'Monday' ? 'selected' : '' }}>Monday</option>
                            <option value="Tuesday" {{ old('day') == 'Tuesday' ? 'selected' : '' }}>Tuesday</option> 
                            <option value="Wednesday" {{ old('day') == 'Wednesday' ? 'selected' : '' }}>Wednesday</option> 
                            <option value="Thursday" {{ old('day') == 'Thursday' ? 'selected' : '' }}>Thursday</option> 
                            <option value="Friday" {{ old('day') == 'Friday' ? 'selected' : '' }}>Friday</option>  
                        </select>
                        @error('day')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Duration</label>
                        <input type="number" name="duration" class="form-control" value="{{ old('duration') }}">
                        @error('duration')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Start Time</label>
                        <input type="text" name="start_time" id="start_time" class="form-control" value="{{ old('start_time') }}">
                        @error('start_time')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">End Time</label>
                        <input type="text" name="end_time" id="end_time" class="form-control" value="{{ old('end_time') }}">
                        @error('end_time')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>  
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
@endsection  
