//Rename Recipe
$('RecipeName').on('click', function() {
	box = $('<input>').attr('type', 'textbox').val($(this).text())

	box.on('blur', function() {
		// make ajax call to save edit

		rid = box.parent().attr("id")
		$.ajax({url : baseurl 
			+ "/handlers/edit_recipe/" 
			+ rid + "/" 
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
//Rename Step
$('step').on('click', function() {
	box = $('<input>').attr('type', 'textbox').val($(this).text())

	box.on('blur', function() {
		// make ajax call to save edit

		sid = box.parent().attr("id")
		rid = $('RecipeName').attr("id")
		$.ajax({url : baseurl 
			+ "/handlers/edit_steps/" 
			+ rid + "/"
			+ sid + "/" 
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

//Add Step
$('#new').on('click',function(){
	console.log("Ho")

	clone = $('li:last');
	num = parseInt( clone.prop("id").match(/\d+/g), 10 ) +1;
	clone = clone.prop('id', num );

	clone.find("step").text("Add Step").val("Add Step")

	clone.insertAfter($('.list').last())




})