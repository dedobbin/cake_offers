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
    Title:<br/> <input type="text" name="title"/><br/>
    Content:<br/> <textarea name = content rows="5"></textarea>

    <input type="submit" value="Submit">
</form> 