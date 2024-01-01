@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8"> 
            <label for="">Select Date For Appointment Booking</label>
            <input type="text" id="datepicker" name="selected_date" class="datepicker form-control">
        </div>
        <div class="col-md-8 mt-4">
            <div id="timeSlots"></div>
        </div>
        <div id="emailInput" class="col-md-8 mt-4" style="display:none;">
            <label for="email">Enter Email:</label>
            <input type="email" id="email" name="email" class="form-control">
        </div>
    </div>
    <button id="bookButton" class="mt-4 btn btn-primary" disabled>Book</button>
</div>
@endsection

@section('script')
<script>
   $(document).ready(function() {
        var allowedDays = {!! json_encode($allowedDays) !!};

        var dayMapping = {
            'Monday': 1,
            'Tuesday': 2,
            'Wednesday': 3,
            'Thursday': 4,
            'Friday': 5,
        };

        allowedDays = allowedDays.map(function(day) {
            return dayMapping[day];
        });

        $("#datepicker").datepicker({
            beforeShowDay: function(date) {
                var day = date.getDay();  
                return [allowedDays.includes(day), ''];
            },
            onSelect: function(dateText, inst) {
                var selectedDate = new Date(dateText);
                var selectedDay = selectedDate.getDay();  
                var selectedDayName = getKeyByValue(dayMapping, selectedDay);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '{{ route("fetchSlot") }}',
                    type: 'POST',
                    data: {
                        selected_day: selectedDayName
                    },
                    success: function(response) {
                        displayTimeSlots(response);
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            }
        });

        function getKeyByValue(object, value) {
            return Object.keys(object).find(key => object[key] === value);
        }

        function displayTimeSlots(timeSlots) {
            $("#timeSlots").empty();  

            var commonLabel = $('<label>Select Time Slot: </label>');

            timeSlots.forEach(function (slot, index) {
                var radioId = 'timeSlot' + index;

                var radioBtn = $('<input type="radio" class="mx-3" id="' + radioId + '" name="timeSlot" value="' + slot.start + ' to ' + slot.end + '">');
                var label = $('<label for="' + radioId + '"> ' + slot.start + ' - ' + slot.end + '</label>');


                commonLabel.append(radioBtn);
                commonLabel.append(label);
            });

            $("#timeSlots").append(commonLabel);

            $('input[name="timeSlot"]').on('change', function() {
                $("#bookButton").prop("disabled", false);
                $("#emailInput").show();
            });

           
            $("#bookButton").on('click', function() {
                var selectedDate = $("#datepicker").val();
                var selectedSlot = $("input[name='timeSlot']:checked").val();
                var email = $("#email").val();
                $("#bookButton").html('Sending...');

                $.ajax({
                    url: '{{ route("bookAppointment") }}',
                    type: 'POST',
                    data: {
                        selected_date: selectedDate,
                        selected_slot: selectedSlot,
                        email: email
                    },
                    success: function(response) {
                        console.log(response);
                        toastr.success('Booking successful! Check your email for details.');
                        $("#timeSlots").empty();  
                        $("#datepicker").val('');
                        $("#email").val('');
                        $('input[name="timeSlot"]').prop('checked', false);
                        $("#emailInput").hide();
                        $("#bookButton").prop("disabled", true);
                        $("#bookButton").html('Book');
                    },
                    error: function(response) {
                        toastr.error(response.responseJSON.error || 'Booking failed. Please try again.');
                        $("#bookButton").html('Book');
                    }
                });
            });

        }
    });
</script>
@endsection
