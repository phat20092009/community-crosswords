var CrossWordFactory = {};
(function($){
	CrossWordFactory.init_list_page = function(){
		$("#menuitems").buttonset();
		$("#menu-create_crossword").bind("click", function(){
			var btn_cancel = $("#lbl_cancel").html();
			var btn_submit = $("#lbl_submit").html();
			var btnopts = {};
			btnopts[btn_cancel] = function(){
				$(this).dialog("close");
			};
			btnopts[btn_submit] = function(){
				$("#crossword-form").submit();
			};
			$("#crossword-form").dialog({
				width: 400,
				modal : true,
				buttons : btnopts,
				show: "slide"
			});
			
			return false;
		});
		$("#menu-submit_question").bind("click", function(){
			var btn_cancel = $("#lbl_cancel").html();
			var btn_ok = $("#lbl_ok").html();
			var btn_submit = $("#lbl_submit").html();
			var btnopts = {};
			btnopts[btn_cancel] = function(){
				$(this).dialog("close");
			};
			
			btnopts[btn_submit] = function(){
				var dialog = jQuery(this);
				$("#progress-confirm").insertAfter($(this)).show();
				$("#question-form").ajaxSubmit({
					dataType:  'json',
					method: 'post',
					success:   function(data){
						var btnopts2 = {};
						btnopts2[btn_ok] = function(){
							$(this).dialog("close");
						};
		                if(typeof data.error != 'undefined' && data.error.length > 0){
							$("<div>",{"title":$("#lbl_error").html()}).html(data.error).dialog({
								modal : true,
								buttons : btnopts2
							});
		                }else{
							$("#question-form").find("input[type='text']").val("");
		                	dialog.dialog("close");
							$("<div>",{"title":$("#lbl_info").html()}).html(data.message).dialog({
								modal : true,
								buttons : btnopts2
							});
		                }
		                $("#progress-confirm").hide();
					}
				});
			};
			$("#question-form").dialog({
				width: 600,
				modal : true,
				buttons : btnopts,
				show: "slide"
			});
			
			return false;
		});
	};
    
	CrossWordFactory.init_details_page = function(){
		$('#crossword-grid').find('input').bind('click', function(event){
			highlight_cells($(this));
		});
		
	    $('#crossword-grid').find('input').bind('keypress', function(event){
	        if($(this).attr("readonly") == false){
		        var c = String.fromCharCode(event.which).toUpperCase();
		        if (event.which == 13){
		            event.preventDefault();
		        }
		        if(is_key_valid(c)){
		            $(this).val(c);
		            move_next($(this));
		        }
		        if(event.which == 8){
		            move_back($(this));
		        }
	        }
	    });

	    $(".question-title").click(function(){
	        $(".question-title").removeClass("highlight");
	        $(this).addClass("highlight");
	        var axis = $(this).attr("id").substring(0, 1);
	        var position = parseInt($(this).attr("id").substring($(this).attr("id").indexOf("-") + 1));
	        $('#crossword-grid').find('td, input').removeClass('rowhighlight colhighlight');
			$(".letters-"+axis+"-"+position).removeClass("highlight");
			$(".letters-"+axis+"-"+position).find("input").removeClass("highlight");
			$(".letters-"+axis+"-"+position).addClass((axis == "2") ? "colhighlight" : "rowhighlight");
			$(".letters-"+axis+"-"+position).find("input").addClass((axis == "2") ? "colhighlight" : "rowhighlight");
		});

	    $("#btn-check-result").button().click(function(){
	        $("#progress-confirm").insertAfter($(this)).show();
	    	$("#crossword-form").ajaxSubmit({
	    		dataType: 'json',
	    		success: function(data){
		    		var btn_cancel = $("#lbl_cancel").html();
					var btnopts = {};
					btnopts[btn_cancel] = function(){
						$(this).dialog("close");
					};
		            if(typeof data.error != 'undefined' && data.error.length > 0){
						$("<div>",{"title":$("#lbl_info").html()}).html(data.error).dialog({
							modal : true,
							buttons : btnopts
						});
		            }else if(typeof data.message != 'undefined' && data.message.length > 0){
		            	$('#crossword-grid').find('input').attr("disabled", true);
						$("<div>",{"title":$("#lbl_info").html()}).html(data.message).dialog({
							modal : true,
							buttons : btnopts
						});
		            }else if(typeof data.failed != 'undefined' && data.failed.length > 0){
		            	$('#crossword-grid').find('input').removeClass('rowhighlight colhighlight');
		            	for(var i=0; i < data.failed.length; i++){
			            	$("."+data.failed[i]).find("input").addClass("highlight");
			            	$("."+data.failed[i]).addClass("highlight");
		            	}
						$("<div>",{"title":$("#lbl_info").html()}).html($("#msg_failed_answers").html()).dialog({
							modal : true,
							buttons : btnopts
						});
		            }
		            $("#progress-confirm").hide();
	    		}
	    	});
		});
	};
	
	function is_key_valid(c){
        return ((c >= 'A' && c <= 'Z') || c == ' ');
    }

    function highlight_cells(current){
        var id = current.attr('id');
        var row = parseInt(id.substring(id.indexOf('_') + 1, id.lastIndexOf('_')));
        var col = parseInt(id.substring(id.lastIndexOf('_') + 1));
        var first_cell = false;
        if(($('#cell_'+(row+1)+'_'+col).length > 0 && $('#cell_'+(row+1)+'_'+col).hasClass('rowhighlight')) || 
        		$('#cell_'+row+'_'+(col+1)).length > 0 && $('#cell_'+row+'_'+(col+1)).hasClass('colhighlight')){
    		return false;
        }else{
        	$('#crossword-grid').find('input').removeClass('rowhighlight colhighlight');
        	 if(($('#cell_'+row+'_'+(col+1)).length > 0) || ($('#cell_'+row+'_'+(col-1)).length > 0)){
             	var i = col;
                 while($('#cell_'+row+'_'+i).length > 0){
                 	$('#cell_'+row+'_'+(i)).removeClass('highlight');
                 	$('#cell_'+row+'_'+(i--)).addClass('colhighlight');
                 }
                 i = col + 1;
                 while($('#cell_'+row+'_'+i).length > 0){
                 	$('#cell_'+row+'_'+(i)).removeClass('highlight');
                 	$('#cell_'+row+'_'+(i++)).addClass('colhighlight');
                 }
             }else if(($('#cell_'+(row+1)+'_'+col).length > 0) || ($('#cell_'+(row-1)+'_'+col).length > 0)){
                var i = row;
                while($('#cell_'+i+'_'+col).length > 0){
                	$('#cell_'+(i)+'_'+col).removeClass('highlight');
                    $('#cell_'+(i--)+'_'+col).addClass('rowhighlight');
                }
                i = row+1;
                while($('#cell_'+i+'_'+col).length > 0){
                	$('#cell_'+(i)+'_'+col).removeClass('highlight');
                	$('#cell_'+(i++)+'_'+col).addClass('rowhighlight');
                }
            }
        }
    }
    
    function move_next(current){
        var id = current.attr('id');
        var row = parseInt(id.substring(id.indexOf('_') + 1, id.lastIndexOf('_')));
        var col = parseInt(id.substring(id.lastIndexOf('_') + 1));
        if($('#cell_'+(row+1)+'_'+col).length > 0 && $('#cell_'+(row+1)+'_'+col).hasClass('rowhighlight')){
        	$('#cell_'+(row+1)+'_'+col).focus();
        }else if($('#cell_'+row+'_'+(col+1)).length > 0 && $('#cell_'+row+'_'+(col+1)).hasClass('colhighlight')){
        	$('#cell_'+row+'_'+(col+1)).focus();
        }
    }

    function move_back(current){
        var id = current.attr('id');
        var row = parseInt(id.substring(id.indexOf('_') + 1, id.lastIndexOf('_')));
        var col = parseInt(id.substring(id.lastIndexOf('_') + 1));
        if($('#cell_'+(row-1)+'_'+col).length > 0 && $('#cell_'+(row-1)+'_'+col).hasClass('rowhighlight')){
        	$('#cell_'+(row-1)+'_'+col).focus();
        }else if($('#cell_'+row+'_'+(col-1)).length > 0 && $('#cell_'+row+'_'+(col-1)).hasClass('colhighlight')){
        	$('#cell_'+row+'_'+(col-1)).focus();
        }
    }
})(jQuery);