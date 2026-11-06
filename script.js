document.getElementById("studentForm").addEventListener("submit", function(event) {
    event.preventDefault();

    var name = document.querySelector('input[name="name"]').value.trim();
    var email = document.querySelector('input[name="email"]').value.trim();

    var nameError = document.getElementById("nameError");
    var emailError = document.getElementById("emailError");

    nameError.textContent = "";
    emailError.textContent = "";

    var xhr = new XMLHttpRequest();

    xhr.open("POST", "validate_student.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = xhr.responseText;

            if (response == "valid") {
                document.getElementById("studentForm").submit();
            } else {
                var errors = response.split(",");

                if (errors.indexOf("name") != -1) {
                    nameError.textContent = "يرجى إدخال اسم الطالب.";
                }

                if (errors.indexOf("email_empty") != -1) {
                    emailError.textContent = "يرجى إدخال البريد الإلكتروني.";
                }

                if (errors.indexOf("email_invalid") != -1) {
                    emailError.textContent = "يرجى إدخال بريد إلكتروني صحيح.";
                }
            }
        }
    };

    xhr.send("name=" + encodeURIComponent(name) +
             "&email=" + encodeURIComponent(email));
});