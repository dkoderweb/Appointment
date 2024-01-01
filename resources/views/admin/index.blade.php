@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8"> 

                <div class="card-body"> 
                    <a href="{{route('slot.create')}}" class="btn btn-primary">Add</a>
                </div> 
                <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Days</th> 
                        <th scope="col">Start Time</th> 
                        <th scope="col">End Time</th> 
                        <th scope="col">Duration</th> 
                        <th scope="col">Action</th> 
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($slots as $slot)
                        <tr>
                          <th>{{$slot->id}}</th>       
                          <th>{{$slot->day}}</th>
                          <th>{{$slot->start_time}}</th>
                          <th>{{$slot->end_time}}</th>
                          <th>{{$slot->duration}}</th>
                          <th style="display: flex;"> 
                            <a href="{{ route('slot.edit', ['slot' => $slot->id]) }}" class='btn btn-primary'>Edit</a> 
                            <form class="delete-form mx-2" method="post" action="{{ route('slot.destroy', ['slot' => $slot->id]) }}">
                                @csrf
                                @method('DELETE')
                    
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                            </th>
                        </tr>                             
                        @endforeach
                    </tbody>
                  </table>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
$(document).ready( function () {
    $('.table').DataTable();
    $('.delete-form').on('submit', function (event) {
        if (!confirm('Are you sure?')) {
            event.preventDefault();  
        }
    });
} );
</script>
@endsection
