<!doctype html>
<html lang="en">
<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>

<form action="">
    <input type="file" name="image">
    <button type="button" class="submit">submit</button>
</form>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script !src="">
    $('.submit').on('click',function (e) {
        e.preventDefault()
        console.log($('input').val())
   $.ajax({
       url:"{{route('dishes.store')}}",
       type: 'POST',
       data:{
          image:$('input').val()
       },success:function (e) {
           console.log(e)
       }

   })
    })
</script>
</body>
</html>
