<form method='POST' action='/users/p_profile_edit'>

        Email:
		<br>
        <input type='text' name='email' value='<?php echo $user->email; ?>'>
        <br><br>

        First Name:
		<br>
        <input type='text' name='first_name' value='<?php echo $user->first_name; ?>'>
        <br><br>

        Last Name:
		<br>
        <input type='text' name='last_name' value='<?php echo $user->last_name; ?>'>
        <br><br>

        Password:
		<br>
        <input type='password' name='password'>
        <br><br>

		Retype Password:
		<br>
        <input type='password' name='password2'>
        <br><br>

        <input type='submit' value='Submit Change'>

</form>
