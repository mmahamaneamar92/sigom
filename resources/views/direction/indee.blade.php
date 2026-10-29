<!DOCTYPE html>
<html>
<head>
<title>Laravel 9 Autocomplete Textbox with JQuery Ajax</title>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />
<style type="text/css">
   .box{
    width:600px;
    margin:0 auto;
   }
</style>
</head>
<body>
<p style="padding:20px;"><h3 align="center">Laravel 9 Autocomplete Textbox with JQuery Ajax</h3></p>
<div class="container box">
   <div class="form-group">
        <input type="text" name="nom_direction" id="nom_direction" class="form-control" placeholder="Enter Direction Name" />
        <div id="countryList"></div>
   </div>
   {{ csrf_field() }}
</div>
<script>
$(document).ready(function(){

    $('#nom_direction').keyup(function(){
        var query = $(this).val();
        if(query != '')
        {
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('fetch') }}",
                method:"POST",
                data:{query:query, _token:_token},
                success:function(data){
                $('#countryList').fadeIn();
                    $('#countryList').html(data);
                }
            });
        }
    });

    $(document).on('click', 'li', function(){
        $('#nom_direction').val($(this).text());
        $('#countryList').fadeOut();
    });

});
</script>
</body>
</html>
