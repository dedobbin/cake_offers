<script>
    $(window).ready(event=>{
        var url = "joboffers/create";
        $('#create_form input[type="submit"]').on('click', event=>{
            event.preventDefault();
            jsonForm(url, $('#create_form'));
        })
    });
</script>

<form id = 'create_form'>
    Title <input type="text" name="title"/><br>
    Content <input type="text" name="content"/><br>
    <input type="submit" value="Submit">
</form> 