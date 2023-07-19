const show_hidePassword = {     
    
    init: function() {
        
        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#user_password");
        togglePassword.addEventListener("click", function () {
            // toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            
            // toggle the icon
            this.classList.toggle("fa-eye");
        });

    },
}

document.addEventListener("DOMContentLoaded", show_hidePassword.init);