jQuery(function() {
    $("#profileUpdate").validate({
        rules: {
            name: {
                minlength: 3,
                required: true
            },
            email: {
                minlength: 3,
                required: true,
                email: true
            }
        },
        errorClass: "help-block error",
        highlight: function(e) {
            $(e).closest(".form-group.row").addClass("has-error")
        },
        unhighlight: function(e) {
            $(e).closest(".form-group.row").removeClass("has-error")
        },
        messages: {
            name: {
                required: "Name is required",
            },
            email: {
                required: "Email is required",
                email: "Enter valid email",
            }
        }  
    });

    // login form 
    $("#loginForm").validate({
        rules: {
            email: {
                minlength: 3,
                required: true,
                email: true
            },
            password: {
                required: true,
            }
        },
        errorClass: "help-block error",
        highlight: function(e) {
            $(e).closest(".form-group.row").addClass("has-error")
        },
        unhighlight: function(e) {
            $(e).closest(".form-group.row").removeClass("has-error")
        },
        messages: {
            email: {
                required: "Email is required",
                email: "Enter valid email",
            },            
            password: {
                required: "Password is required",
            }
        }        
    });

    // user create form 
    $("#createUser").validate({
        rules: {
            name: {
                minlength: 3,
                required: true
            },
            email: {
                minlength: 3,
                required: true,
                email: true
            },
            password: {
                required: true,
            },
            confirm_password: {
                required: true,
                equalTo: "#password"
            },
            "roles[]": {
                required: false,
            }
        },
        errorClass: "help-block error",
        highlight: function(e) {
            $(e).closest(".form-group.row").addClass("has-error")
        },
        unhighlight: function(e) {
            $(e).closest(".form-group.row").removeClass("has-error")
        },
        messages: {
            name: {
                required: "Name is required",
            },
            email: {
                required: "Email is required",
                email: "Enter valid email",
            },            
            password: {
                required: "Password is required",
            },            
            confirm_password: {
                required: "Password is required",
                equalTo: "Confirm password should be same as password field",
            },             
            "roles[]": {
                required: "Role is required",
            }
        }        
    });

    // user update form 
    $("#updateUser").validate({
        rules: {
            name: {
                minlength: 3,
                required: true
            },
            email: {
                minlength: 3,
                required: true,
                email: true
            },
            "roles[]": {
                required: false,
            }
        },
        errorClass: "help-block error",
        highlight: function(e) {
            $(e).closest(".form-group.row").addClass("has-error")
        },
        unhighlight: function(e) {
            $(e).closest(".form-group.row").removeClass("has-error")
        },
        messages: {
            name: {
                required: "Name is required",
            },
            email: {
                required: "Email is required",
                email: "Enter valid email",
            },
            "roles[]": {
                required: "Role is required",
            }
        }        
    });

    // role create form 
    $("#roleCreate").validate({
        rules: {
            name: {
                minlength: 3,
                required: true
            },
            "permission[]": {
                required: true                
            }
        },
        errorClass: "help-block error",
        highlight: function(e) {
            $(e).closest(".form-group.row").addClass("has-error")
        },
        unhighlight: function(e) {
            $(e).closest(".form-group.row").removeClass("has-error")
        },
        messages: {
            name: {
                required: "Name is required",
            },
            "permission[]": {
                required: "Select a checkbox",
            }
        }        
    });

    // role update form 
    $("#roleUpdate").validate({
        rules: {
            name: {
                minlength: 3,
                required: true
            },
            "permission[]": {
                required: true                
            }
        },
        errorClass: "help-block error",
        highlight: function(e) {
            $(e).closest(".form-group.row").addClass("has-error")
        },
        unhighlight: function(e) {
            $(e).closest(".form-group.row").removeClass("has-error")
        },
        messages: {
            name: {
                required: "Name is required",
            },
            "permission[]": {
                required: "Select a checkbox",
            }
        }        
    });

});