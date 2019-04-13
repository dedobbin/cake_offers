<script>
    $(window).ready(event=>{
        var url = "<?= $this->Url->build('/users/register', [
            'escape' => false,
            'fullBase' => true,
        ]);?>";
        $('#register_form input[type="submit"]').on('click', event=>{
            event.preventDefault();
            jsonForm(url, $('#register_form'));
        })
    });
</script>


<form id = 'register_form'>
    First name <input type="text" name="first_name"/><br>
    Last name <input type="text" name="last_name"/><br>
    Username <input type="text" name="username"/><br>
    Email address: <input type="text" name="email_address"/><br>
    Password <input type="password" name="password"/><br>
    Retype password <input type="password" name="password_retype"/><br>
<input type="submit" value="Submit">
</form> 