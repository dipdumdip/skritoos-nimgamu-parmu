
	var scrollspeed=2;		//<---stock market ticker flow speed
	var pxptick=scrollspeed;

	function PLOT_the_Graph (company_symbol, accuracy ) {		//<---this function calls to update the stock graph
		$("#graph_getting").attr("src", APP_URL+"get_graph_data?company="+company_symbol+'&prc='+accuracy);
		setTimeout(function(){
			$("#graph_getting").fadeIn(1000);
		}, 3000);
	}

	function update_the_Prediction_list( ) {		//<---this function calls to update last 5 prediction  AJAx via
			$.ajax({
				type: "GET",
				url: APP_URL+"get_last_predictions",
				beforeSend: function(){	
					$("#result_out_wrap #last_predict").html('<img style="margin:10px 35%;" src="'+APP_URL+'img/icons/ajaxloader_hr.gif"  />'); 
				},
				success: function(data) {
						$("#result_out_wrap #last_predict").html(data);
				}
			});
	}


	function submit_dat(){		//<--- this function does the actio on prediction. 
			var company_symbol= $("#company_symbol").val(),
				accuracy= $("#accuracy_change").val();
				$.ajax({
					type: "GET",
					data: 'company='+company_symbol+'&prc='+accuracy,
					url: APP_URL+"get_stockmarket_data",
					beforeSend: function(){	
						$("#stockTicker_cont").hide(); 
						$("#ajax_image").show(); 
					},
					success: function(data) {
							if(data.length>0 && data.length<100){
								// alert(data.length)
								window.open(data, "_self");	
							}else{
								alert("Please Type A Valid Company Symbol")
								$("#stockTicker_cont").show(); 
								$("#ajax_image").hide(); 
							}
						return true;
					}
			});	
	}
	
		// Set an initial scroll speed. This equates to the number of pixels shifted per tick

	// Set an initial scroll speed. This equates to the number of pixels shifted per tick
	function startTicker(){	//<--- funtion calls to do ticker flow initializer
		// Make a shortcut referencing our div with the content we want to scroll
		tickerWrapDiv=document.getElementById("stockTicker_cont");
		// Get the total width of our available scroll area
		tickerWrapWidth=document.getElementById("stockTicker_wrap").offsetWidth;
		// Get the width of the content we want to scroll
		contentwidth=tickerWrapDiv.offsetWidth;
		// Start the ticker at 50 milliseconds per tick, adjust this to suit your preferences
		// Be warned, setting this lower has heavy impact on client-side CPU usage. Be gentle.
		setInterval("scrollticker()",50);
	}
	
	function scrollticker(){		//<--this functio calls to scroll the horizontal list
		// Check position of the div, then shift it left by the set amount of pixels.
		if (parseInt(tickerWrapDiv.style.left)>(contentwidth*(-1)))
			tickerWrapDiv.style.left=parseInt(tickerWrapDiv.style.left)-pxptick+"px";
		// If it's at the end, move it back to the right.
		else
			tickerWrapDiv.style.left=parseInt(tickerWrapWidth)+"px";
	}

	$(function() {
		
		startTicker();	//<--- funtion calls to do ticker flow initializer
		$("#company_symbol").focus();
		$(document).on("click", "#check_result", function(){	//<--this clicks to load new  record onto the board
			var company_symbol= $("#company_symbol").val(),
				accuracy= $("#accuracy_change").val();
			if(company_symbol.length > 0) {	//<----checks for the symbols accuracy
					submit_dat();	//<--- this function does the actio on prediction. 
			}else{	$("#company_symbol").val("").focus();
					alert("Please Type A Valid Company Symbol")
			}
		});

	});
	

		$(document).on("keyup", "#company_symbol", function(e){
			var value =$(this).val();
				if(value.length>0){
					if(e.which==13){
							submit_dat();	//<--- this function does the actio on prediction. 
							$("#work_field .work_field_msg").fadeOut("slow");
					}else{
						$("#work_field .work_field_msg").fadeIn("slow");
					}
				}else{
					$("#work_field .work_field_msg").fadeOut("slow");
				}

		}).on("mouseup", "body", function(e){
			$("#work_field .work_field_msg").fadeOut("slow");
		});
		
	
	
	
