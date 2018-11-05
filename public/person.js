$('phone-number').on('click', function() {
	box = $('<input>').attr('type', 'textbox').val($(this).text())

	box.on('blur', function() {
		// make ajax call to save edit

		pnid = box.parent().attr("id")
		$.ajax({url : baseurl 
			+ "/handlers/edit_phone_number/" 
			+ pnid + "/" 
			+ $(this).val(),
			dataType : "text"})
		.done(function() {
			// deal with error code in response

			// put back on page
			$(this).parent().text($(this).val())
		})
	})

	$(this).text("")
	$(this).append(box)
	box.select()
})