<link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/css/bootstrap.css" rel="stylesheet"/>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/js/bootstrap.js"></script>

<script type="text/javascript" src="typeahead.js"></script>

<div class="container" dir="rtl">
	

	<div class="row">
		
		<div class="col-md-6">
			
		</div>
		<div class="col-md-6">
			<form dir="rtl" id="get_zip" method="post">
				<div class="form-group">
					<label>עיר</label>
					<input class="form-control autocompleteCity typeahead" placeholder="הכנס שם עיר" required="" />
				</div>

				<div class="form-group">
					<label>כתובת</label>
					<input class="form-control autocompleteAdress typeahead" placeholder="הכנס כתובת" required="" />
				</div>

				<div class="form-group">
					<label>מספר בית</label>
					<input class="form-control autocompleteHome typeahead" placeholder="הכנס מספר בית" required="" />
				</div>

				<div class="form-group">
					<label>כניסה</label>
					<input class="form-control autocompleteEnter typeahead" placeholder="הכנס כניסה" />
				</div>

				<button type="submit" >שליחה</button>

				<div class="form-group">
					<span id="set_zip"></span>
				</div>

			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function () {

	$('.autocompleteCity').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "action.php?action=autocompleteCity",
				data: 'city=' + query,            
                dataType: "json",
                type: "POST",
                success: function (data) {
                	//console.log(data['locations']);
                	data = data['locations'];

					var results = [];

					$.each(data, function(key,value){
					    results.push(value.n);
					});

					result($.map(results, function (item) {
						return item;
                    }));
				
                }
            });
        }
    });

	
    $('.autocompleteAdress').typeahead({
		source: function (query, result) {
        var city = $('.autocompleteCity').val();
		$.ajax({
                url: "action.php?action=autocompleteAdress",
				data: 'address=' + query+'&city='+city,            
                dataType: "json",
                type: "POST",
                success: function (data) {
                	data = data['streets'];
					var results = [];
					$.each(data, function(key,value){
					    results.push(value.n);
					});

					result($.map(results, function (item) {
						return item;
                    }));
				
                }
            });
        }
    });


    $("#get_zip").submit(function (e) {
	    e.preventDefault();
	    var city = $('.autocompleteCity').val();
	    var address = $('.autocompleteAdress').val();
	    var home = $('.autocompleteHome').val();
	    var enter = $('.autocompleteEnter').val();

	    $.ajax({
            url: "action.php?action=get_zip",
			data: 'address=' + address+'&city='+city+'&home='+home+'&enter='+enter,            
            dataType: "json",
            type: "POST",
            success: function (data) {
            	if (data == 0){
            		$('#set_zip').text("לא נמצא מיקוד לכתובת זו");
            	}else{
            		$('#set_zip').html("<strong>מיקודך הוא:</strong>"+data);
            	}
            				
            }
        });
	});

    

    



	/*$('.autocompleteCity').autocomplete({
	    serviceUrl: 'action.php?action=autocompleteCity',
	    onSelect: function (suggestion) {
	        console.log(suggestion);
	    }
	});*/
	/*
	  $(".autocomplete").autocomplete({
	    source: availableTags
	  });*/
});
</script>