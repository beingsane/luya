var observeLogin = function(form, url, secureUrl) {
	$(form).submit(function(e) {
        $('#errorsContainer').hide();
        $('.login-animation').addClass('login-animation--is-loading');
		e.preventDefault();
		$.ajax({
			type: "POST",
			url: url,
			data: $(this).serialize(),
			success : function(response) {
				var refresh = response['refresh'];
				var errors = response['errors'];
				var enterSecureToken = response['enterSecureToken'];
				
				var errorHtml = '<ul>';
				for(var i in errors) {
					errorHtml = errorHtml + '<li>' + errors[i] + '</li>';
				}
				errorHtml = errorHtml + '</ul>';
				
				if (errors) {
                    $('.login-animation').removeClass('login-animation--is-loading');
					$('#errorsContainer').html(errorHtml);
					$('#errorsContainer').show();
				}
				
				
				if (enterSecureToken) {
					$('#secureForm').show();
					$('#loginForm').hide();
				}
				
				if (refresh) {
                    $('.login-animation').removeClass('login-animation--is-loading').addClass('login-animation--active');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
				}
			},
			dataType: "json"
		});
	});
	
	$('#secureForm').submit(function(e) {
		$('#errorsContainer').hide();
        $('.login-animation').addClass('login-animation--is-loading');
		e.preventDefault();
		$.ajax({
			type: "POST",
			url: secureUrl,
			data: $(this).serialize(),
			success : function(response) {
				var refresh = response['refresh'];
				
				if (response['errors']) {
                    $('.login-animation').removeClass('login-animation--is-loading');
					$('#errorsContainer').html('<ul><li>' + response['errors'] + '</li></ul>');
					$('#errorsContainer').show();
				}
				
				if (refresh) {
                    $('.login-animation').removeClass('login-animation--is-loading').addClass('login-animation--active');
                    $('#secureForm').hide();
                    $('#loginForm').hide();
					$('#success').show();
                    location.reload();
				}
			},
			dataType: "json"
		})
	});
	
	$('#abortToken').click(function(e) {
        $('.login-animation').removeClass('login-animation--is-loading');
		$('#errorsContainer').hide();
		$('#secureForm').hide();
		$('#loginForm').show();
		$('#success').hide();
	});
};