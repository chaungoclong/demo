<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <link rel="stylesheet" href="">
    <script src="dist/jquery/jquery-3.5.1.js"></script>
    <script src="dist/jquery-validation/dist/jquery.validate.js"></script>
</head>

<body>
    <form action="" name="registration" id="form">

        <label for="firstname">First Name</label>
        <input type="text" name="firstname" id="firstname" placeholder="John"required />
        <br><br>

        <label for="lastname">Last Name</label>
        <input type="text" name="lastname" id="lastname" placeholder="Doe" />
        <br><br>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="john@doe.com" />
        <br><br>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;" />
        <br><br>

        <button type="submit">Register</button>

    </form>
    <script>
        $(function() {
            $.validator.addMethod(
                'checkPwd',
                function(val, el) {
                    return this.optional(el) || /^([\w\d]{8,32})$/.test(val);
                },
                "mat khau sai"
            );
//            $.validator.setDefaults({
//                debug: true,
//                success: "valid"
//            });
            $("#form").validate({
                // Specify validation rules

                rules: {
                    // The key name on the left side is the name attribute
                    // of an input field. Validation rules are defined
                    // on the right side
                    firstname: "required",
                    lastname: "required",
                    email: {
                        required: true,
                        // Specify that email should be validated
                        // by the built-in "email" rule
                        email: true
                    },
                    password: {
                        required: true,
                        checkPwd: true
                    },
                    hello: {
                        required: true,
                        checkPwd: true
                    }
                },
                // Specify validation error messages
                messages: {
                    firstname: "Please enter your firstname",
                    lastname: "Please enter your lastname",
                    password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 5 characters long",
                        checkPwd: "hellook"
                    },
                    email: "Please enter a valid email address"
                },
                // Make sure the form is submitted to the destination defined
                // in the "action" attribute of the form when valid
                submitHandler: function(form) {
                    console.log($(form).serializeArray());
                }
            });
        })

    </script>
</body>

</html>
