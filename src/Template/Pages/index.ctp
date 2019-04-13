<script>
    $(window).ready( event=>{
        if (window.sessionStorage.getItem('username'))
            $('#login_form').hide();

        var formData = [];
        $('#login_form input[type="submit"]').on('click', event=>{
            var url = "<?= $this->Url->build('/users/login', ['escape' => false, 'fullBase' => true,]);?>";
            event.preventDefault();
            jsonForm(url, $('#login_form'));
        }) 
        $('#logout button').on('click', event=>{
            event.preventDefault();
            var url = "<?= $this->Url->build('/users/logout', ['escape' => false, 'fullBase' => true,]);?>";
            sendJson(url, "post", [], response =>{
                $('#ajaxResponse').text(response.message)
            });
        })
    })
</script>
<form id = 'login_form'>
    Username <input type="text" name="username"/><br>
    Password <input type="password" name="password"/><br>
    <input type="submit" value="Login">
</form>
<div id = 'logout'>  
    <button>Logout</button>
</div> 
<div>
    <p><a href="register">Register</a></p>
    <p><a href="joboffers">View all job offers</a></p>
    <p><a href="create">Create new job offer</a></p>
    <p><a href="edit">Edit/delete my job offers</a>
</div>