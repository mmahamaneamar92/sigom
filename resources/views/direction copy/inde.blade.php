@extends('layouts.ap')

@section('content')<body>
    <div class="container">
        <p>
        <h1>Laravel 9 Auto populate Dropdown with jQuery AJAX</h1>
        </p>
        <div class="row">
            <div class="col-12">
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label"><b>Direction</b></label>
                    <div class="col-sm-10">
                        <select id="direction" name="direction" class="form-control">
                            <option value=""></option>
                            @foreach ($directions as $direction)
                                <option value="{{ $direction->nom_direction }}">{{ $direction->nom_direction }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label"><b>Service</b></label>
                    <div class="col-sm-10">
                        <select id='nom_service' name='nom_service' class="form-select">
                            <option value='0'>-- Select Employee --</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type='text/javascript'>
        $(document).ready(function() {

            $('#direction').change(function() {
                // Department id
                var id = $(this).val();

                // Empty the dropdown
                $('#nom_service').find('option').not(':first').remove();

                // AJAX request
                $.ajax({
                    url: 'getServices/' + id,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var len = 0;
                        if (response['data'] != null) {
                            len = response['data'].length;
                        }

                        if (len > 0) {
                            // Read data and create <option >
                            for (var i = 0; i < len; i++) {
                                var id = response['data'][i].id;
                                var name = response['data'][i].nom_service;
                                var option = "<option value='" + id + "'>" + name + "</option>";
                                $("#nom_service").append(option);
                            }
                        }
                    }
                });
            });
        });
    </script>
</body>
@endsection
