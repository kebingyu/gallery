$(document).ready(function() {

	/* Sliding Login Begin */	
	// Expand Panel
	$("#open").click(function(){
		$("div#panel").slideDown("slow");
		return false;	
	});	
	
	// Collapse Panel
	$("#close").click(function(){
		$('input.pnl_pwd').val('');
		$("div#panel").slideUp("slow");	
		return false;	
	});		
	
	// Switch buttons from "Log In | Register" to "Close Panel" on click
	$("#toggle a").click(function () {
		$("#toggle a").toggle();
	});		

	// register form
	$('#btn_register').click(function() {
		register();
		return false;
	});

	$('#reg_form').keypress(function (e) {
		if (e.which == 13) {
			register();
			return false;
		}
	});

	isValidateReady('validateRegisterForm');

	// login form
	$('#btn_login').click(function() {
		login();
		return false;
	});

	$('#login_form').keypress(function (e) {
		if (e.which == 13) {
			login();
			return false;
		}
	});

	isValidateReady('validateLoginForm');
	/* Sliding Login End */	

	// register ajax loading event
	$("body").on({
		ajaxStart: function() {
			$(this).addClass("loading");
		},
		ajaxStop: function() {
			$(this).removeClass("loading");
		}    
	});

});

function call_func(func) {
    return this[func].apply(this, Array.prototype.slice.call(arguments, 1));
}

function isPluginReady(plugin, callback, params) {
    if (typeof plugin == 'string' && (eval('typeof ' + plugin) == 'function')
            || (eval('typeof ' + plugin) == 'object')) {
        if (params != undefined) {
            call_func(callback, params);
        } else {
            call_func(callback);
        }
    } else {
        if (params != undefined) {
            window.setTimeout("isPluginReady('" + plugin + "','" + callback
                    + "','" + params + "');", 100);
        } else {
            window.setTimeout("isPluginReady('" + plugin + "','" + callback
                    + "');", 100);
        }
    }
}

function isValidateReady(callback) {
    if (typeof isPluginReady != 'undefined') {
        isPluginReady('jQuery().validate', callback);
    } else {
        window.setTimeout("isValidateReady();", 100);
    }    
}

function validateRegisterForm(){
    $('#reg_form').validate({
        errorLabelContainer: $('#pnl_jserror'),
        messages: {
            signup: {
                required: '* Register: Please enter user name.'
            },
            reg_pwd: {
                required: '* Register: Please enter password.'
            },
            conf_pwd: {
                required: '* Register: Please confirm your password.'
            }, 
            pcode: { 
                required: '* Register: Please enter promotion code.'
            }
        },
        rules: {
            signup: {
                required: true
            },      
            reg_pwd: {
                required: true
            },      
            conf_pwd: {
                required: true
            },
            pcode: {
                required: true
            }
        }
    });
}      


function register() {
	var str = $('#reg_form').serialize();
	$.post('/services/register', str, function(data) {
		if (data.register == 1) {
			$('#pnl_error').val('');
			$('#pnl_jserror').val('');
			window.location.href = '/profile/index';
		} else {
			var html = "";
			if (data.error) {
				for (var i in data.error) {
					html += data.error[i] + '<br/>';
				}
			}
			$('#pnl_error').html(html);
		}
	}, "json");
	return false;
}

function validateLoginForm(){
    $('#login_form').validate({
        errorLabelContainer: $('#pnl_jserror'),
        messages: {
            login: {
                required: '* Login: Please enter user name.'
            },
            login_pwd: {
                required: '* Login: Please enter password.'
            },
        },
        rules: {
            login: {
                required: true
            },      
            login_pwd: {
                required: true
            },      
        }
    });
}      

function login() {
	var str = $('#login_form').serialize();
	$.post('/services/login', str, function(data) {
		if (data.login == 1) {
			$('#pnl_error').val('');
			$('#pnl_jserror').val('');
			window.location.href = '/profile/index';
		} else {
			var html = "";
			if (data.error) {
				for (var i in data.error) {
					html += data.error[i] + '<br/>';
				}
			}
			$('#pnl_error').html(html);
		}
	}, "json");
	return false;
}

function zebra_center(log, duration) {
    new $.Zebra_Dialog(log, {
        'button'     : false,
        'modal'      : false,
        'auto_close' : typeof duration !== 'undefined' ? duration : 2000
    });
}

function zebra_corner(log, duration) {
    new $.Zebra_Dialog(log, {
        'button'     : false,
        'modal'      : false,
        'position'   : ['right - 20', 'top + 60'],
        'auto_close' : typeof duration !== 'undefined' ? duration : 2000
    });
}

function OnFocus (id, value) {
	obj = document.getElementById(id);
	obj.value = (obj.value == value) ? '' : obj.value;
	obj.select();
}

function OnBlur (id, value) {
	obj = document.getElementById(id);
	obj.value = (obj.value == '') ? value : obj.value;				
}
