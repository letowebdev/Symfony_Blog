    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function () {
        'use strict';

        window.addEventListener('load', () => {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            let forms = document.getElementsByClassName('needs-validation');

            // Loop over them and prevent submission
            let validation = Array.prototype.filter.call(forms, (form) => {
                form.addEventListener('click', (event) => {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();


    let password = document.getElementById("password"), confirm_password = document.getElementById("confirm_password");

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

    function delete_video(e,video_id)
    {
        e.preventDefault();
        if(confirm('Are you sure?')) 
        {
            console.log(id);
            // delete post
            // window.location.href = 'clear database record with Symfony';
        }
    }