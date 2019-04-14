<script>
$(window).ready( event=>{
    $('#logout').on('click', event=>{
        event.preventDefault();
        var url = "<?= $this->Url->build('/users/logout', ['escape' => false, 'fullBase' => true,]);?>";
        sendJson(url, "post", [], response =>{
            $('#ajaxResponse').text(response.message);
        });
    })
})
</script>

<div>
    <p><a href="register">Register</a></p>
    <p><a href="login">Login</a></p>
    <p><a id ="logout">Logout</a></p>
    <p><a href="joboffers">View all job offers</a></p>
    <p><a href="create">Create new job offer</a></p>
    <p><a href="edit">Edit/delete my job offers</a>
</div>