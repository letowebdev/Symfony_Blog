    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function () {
        'use strict';

        window.addEventListener('load', function () {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');

            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function (form) {
                form.addEventListener('click', function (event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();


    var password = document.getElementById("user_password_first"), confirm_password = document.getElementById("user_password_second");

    function validatePassword() {
        if (password.value != confirm_password.value) {
            confirm_password.setCustomValidity("Passwords Don't Match");
        } else {
            confirm_password.setCustomValidity('');
        }
    }
    password.onchange = validatePassword;
    confirm_password.onkeyup = validatePassword;


    $('input[type="file"]').change( (e) => {
        let fileName = e.target.files[0].name;
        console.log(e.target.files[0]);
        $('.custom-file-label').html(fileName);
    });

    // function delete_post(e,post_id)
    // {
    //     e.preventDefault();
    //     if(confirm('Are you sure?')) 
    //     {
    //         console.log(id);
    //         // delete post
    //         // window.location.href = 'clear database record with Symfony';
    //     }
    // }

    function delete_post(e)
    {
        confirm('Are you sure?');
    }