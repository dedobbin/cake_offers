<script>
    $(window).ready( event=>{
        var formData = [];
        $('#login_form input[type="submit"]').on('click', event=>{
            var url = "users/login";
            event.preventDefault();
            jsonForm(url, $('#login_form'), 'index');
        }) 
    })
</script>
<h1>Login</h1>
<form id = 'login_form'>
    Username <input type="text" name="username"/><br>
    Password <input type="password" name="password"/><br>
    <input type="submit" value="Login">
</form>