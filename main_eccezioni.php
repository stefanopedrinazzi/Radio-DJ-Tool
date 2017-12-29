<?php	include("FunctionNew.php"); ?>
<!DOCTYPE html>
<html>
	
	<title>Eccezioni</title>
	
	<script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/calendar.min.js"></script>
	<link rel="stylesheet" href="js/calendar.min.css" />

  	<link rel="stylesheet" type="text/css" href="Semantic/semantic.min.css">
  	<script src="js/semantic.min.js"></script>
  	
	<script language='javascript'>


	<?php $song_ID = $_POST['get_song']; 

		$ID_song=$song_ID; ?>;


	var ID_song= <?php echo $ID_song; ?>;

	var ID_exc= <?php 

				$ID_exc=Get_exception($ID_song);

				echo $ID_exc; ?>;

	var date_array= <?php $date_array=Get_date($ID_song); 

				echo ($date_array);

				?>


	var day=0;
	var date_start="";
  	var date_end="";
  	var flag="";
  	var ore = 23;
	var giorno = 6;
	var array_ecc="";
	var ExceptionID="";
	var modify="";
	var control;


  	eccezione = new Array();
			eccezione[0]=new Array();
			eccezione[1]=new Array();
			eccezione[2]=new Array();
			eccezione[3]=new Array();
			eccezione[4]=new Array();
			eccezione[5]=new Array();
			eccezione[6]=new Array();

	
	function set_array(){
			for(var y=0;y<=giorno;y++){
				for(var x=0;x<=ore;x++){		
					eccezione[y][x]='0';
				}
			}		
	}

	//funzione per la compilazione della matrice utilizzando i checkbox
	function valida_ore(day){
   				for (var x=0; x<=ore; x++){
   					if(eccezione[day][x]=='1'){
   						$('.checkbox').each(function() {
   							var appoggio=$(this).find('.orario').attr("name");
   							if(appoggio-1==x){
   								$(this).find('.orario').prop("checked",true);
   								$(this).find('.orario').attr("checked",true);
   							}
   						});
   					}
				}
	}

	function change_tab(){
			if( $('.ui .item.active').attr('data-tab') == 'lun' ) {
   				day=0;
   				valida_ore(day);
			}	
   			if( $('.ui .item.active').attr('data-tab') == 'mar' ) {
   				day=1;
   				valida_ore(day);
     		}
     		if( $('.ui .item.active').attr('data-tab') == 'mer' ) {
   				day=2;
   				valida_ore(day);
     		}
     		if( $('.ui .item.active').attr('data-tab') == 'gio' ) {
   				day=3;
   				valida_ore(day);
     		}
     		if( $('.ui .item.active').attr('data-tab') == 'ven' ) {
   				day=4;
   				valida_ore(day);
     		}
     		if( $('.ui .item.active').attr('data-tab') == 'sab' ) {
   				day=5;
   				valida_ore(day);
     		}
     		if( $('.ui .item.active').attr('data-tab') == 'dom' ) {
   				day=6;
   				valida_ore(day);
     		}
	}
	
	set_array();


	$(document).ready(function(){

		if (ID_exc==0) {
			
			$("#datecal").hide();
			$("#attivadata").hide();
			$(".select_exc").hide();
			$("#elimina").hide();
			$("#infromazioni").show();

			set_array();
			
			modify=0;
		
		} else {
				
			modify=1;
			var y=0;
			ID_exc.forEach(function(x){
				$('#exception').append($('<option>', {
   					value: x,
   					text: date_array[y]

				}));
			y++;
		});

		$("#datecal").show();
		$("#attivadata").show();
		$("#salva").find("label").text("Aggiungi");
		$("#elimina").hide();
		$("#infromazioni").hide();

		}

		
		$('#exception').on('change',function(){

			$('.checkbox').each(function() {

				$(this).find('.orario').prop("checked",false);
				$(this).find('.orario').attr("checked",false);

			});

		ExceptionID = $('#exception').val();

		if (ExceptionID==0) {
			$("#datecal").show();
			$("#salva").find("label").text("Aggiungi");
			$("#elimina").hide();
			$("#attivadata").show();
			$('.checkbox').find('.active_date').prop("checked",false);
			$('.checkbox').find('.active_date').attr("checked",false);
			

		} else {
			$("#salva").find("label").text("Modifica");
			$("#elimina").show();
		}


		$('#rangestart').calendar('set date',"");
		$('#rangeend').calendar('set date',"");
		
		set_array();
	
		$.ajax({	
			type: "POST",
			url: "Select_exception.php",
			data: { ID_song: ID_song, ExceptionID: ExceptionID},
			success:function(data){
  				var Data = JSON.parse(data);
  				date_s= Data.data_start;
  				date_e= Data.data_end;
  				array_ecc= Data.eccezione;

  				if(date_s==="0000-00-00"){
  					$("#datecal").hide();

  				} else {

  					$("#datecal").show();
  				}

  				if (ExceptionID==0) {
  					$("#datecal").show();
  					$("#attivadata").show();
  					$('#rangestart').calendar('set date',"");
					$('#rangeend').calendar('set date', "");
					
					set_array();


	  				$('.checkbox').find('.active_date').prop("checked",false);
					$('.checkbox').find('.active_date').attr("checked",false);
						
  				} else {

					$("#attivadata").hide();
		  			
		  			date_start = new Date(date_s);
	  				date_end = new Date(date_e);
	
	  				$('#rangestart').calendar('set date', date_start);
					$('#rangeend').calendar('set date', date_end);

					var app=24;
					
					for(var i=0;i<7;i++){
						
							var slice=array_ecc.slice((app*i),app*(i+1));
							var split=slice.split("");
							eccezione[i]=split;	
							}
				}

				change_tab();

				if (ExceptionID!=0) {
					if (date_s.indexOf("000")>-1) {

	 					$('.checkbox').find('.active_date').prop("checked",false);
						$('.checkbox').find('.active_date').attr("checked",false);
					}  				
				}

			}
			
		});

		});


	//funzioni che vengono attivate al cambiamento del tab
	
   	$('.ui .item').on('click', function() {

		$('.checkbox').find('.orario').prop("checked",false);
    	$('.ui .item').removeClass('active');
      	$(this).addClass('active');

   		$('.checkbox').each(function() {
     		
     		$(this).find('.orario').removeClass("hidden");
     		$(this).find('.orario').removeAttr("tabindex");
     		$(this).find('.orario').attr("checked",false);
     			
     	});

   	//richiamo della funzione per validare le ore al cambio di tab

   			change_tab();

    //alert(eccezione[0] + "\n" +eccezione[1] + "\n" +eccezione[2] + "\n" +eccezione[3] + "\n" +eccezione[4] + "\n" +eccezione[5] + "\n" +eccezione[6]);

   	});		

   	//parte di codice richiamata al variare di un valore di checkbox

		$('.checkbox').on('click', function() {
			$('.checkbox').each(function() {
			var count=$(this).find('.orario').attr("name");

			$(this).find('.orario').removeClass("checked");
			$(this).find('.orario').removeClass("hidden");
			$(this).find('.orario').removeAttr("tabindex"); 	
				if( $(this).find('.orario').is(":checked")){
					$(this).find('.orario').prop("checked",true);
					$(this).find('.orario').attr("checked",true);

					eccezione[day][count-1]='1';

				}else{
					$(this).find('.orario').prop("checked",false);
					$(this).find('.orario').attr("checked",false);
					eccezione[day][count-1]='0';

				}

			});
	
		});


		//funzione di check all
    	$('.check.button').on('click', function() {
    		$('.ui .item .active .checkbox').checkbox('attach events', '.check.button', 'check');
    			$('.checkbox').each(function() {
    				var count=$(this).find('.orario').attr("name");
        			$(this).find('.orario').removeClass("checked");
	        		$(this).find('.orario').removeClass("hidden");
	        		$(this).find('.orario').removeAttr("tabindex"); 
	        		$(this).find('.orario').attr("checked",true);
	        		$(this).find('.orario').prop("checked",true);
	        		eccezione[day][count-1]='1'; 
     		});
    	}); 
  		
  		//funzione di uncheck all
  		$('.uncheck.button').on('click', function() {
    		$('.ui .item .active .checkbox').checkbox('attach events', '.uncheck.button', 'check');
    			$('.checkbox').each(function() {
    				var count=$(this).find('.orario').attr("name");
        			$(this).find('.orario').removeClass("checked");
	        		$(this).find('.orario').removeClass("hidden");
	        		$(this).find('.orario').removeAttr("tabindex"); 
	        		$(this).find('.orario').attr("checked",false);
	        		$(this).find('.orario').prop("checked",false);
	        		eccezione[day][count-1]='0'; 
     		});
    	});
	 

	//calendario data start e data end

		$('#rangestart').calendar({
  		
  			 monthFirst: false,

  			text: {
     				days: ['Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab'],
     			 	months: ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'],
      
    		},
  			type: 'date',
  			
  			formatter: {
       			 date: function (date, settings) {
            		if (!date) return '';
            		var day = date.getDate() + '';
            		if (day.length < 2) {
                		day = '0' + day;
            		}
            		var month = date.getMonth();
            		
            		switch(month) {
    					case 0:
        					month = "Gennaio";
        					break;
					    case 1:
					        month = "Febbraio";
					        break;
					    case 2:
					        month = "Marzo";
					        break;
            			case 3:
        					month = "Aprile";
        					break;
					    case 4:
					        month = "Maggio";
					        break;
					    case 5:
					        month = "Giugno";
					        break;
					   	case 6:
        					month = "Luglio";
        					break;
					    case 7:
					        month = "Agosto";
					        break;
					    case 8:
					        month = "Settembre";
					        break;
					    case 9:
        					month = "Ottobre";
        					break;
					    case 10:
					        month = "Novembre";
					        break;
					    case 11:
					        month = "Dicembre";
					        break;
					    
					}
            		return day + ' , ' + month;
        		}
    		},
  			
  			endCalendar: $('#rangeend'),
		
			onChange: function (date, text) {
					$('.checkbox').find('.active_date').prop("checked",true);
					$('.checkbox').find('.active_date').attr("checked",true);
     			date_start = text;
    		},


		});

		$('#rangeend').calendar({

			monthFirst: false,

			text: {
     				days: ['Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab'],
     			 	months: ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'],
      
    		},
  			type: 'date',

  			formatter: {
       			 date: function (date, settings) {
            		if (!date) return '';
            		var day = date.getDate() + '';
            		if (day.length < 2) {
                		day = '0' + day;
            		}
            		var month = date.getMonth();
            		
            		switch(month) {
    					case 0:
        					month = "Gennaio";
        					break;
					    case 1:
					        month = "Febbraio";
					        break;
					    case 2:
					        month = "Marzo";
					        break;
            			case 3:
        					month = "Aprile";
        					break;
					    case 4:
					        month = "Maggio";
					        break;
					    case 5:
					        month = "Giugno";
					        break;
					   	case 6:
        					month = "Luglio";
        					break;
					    case 7:
					        month = "Agosto";
					        break;
					    case 8:
					        month = "Settembre";
					        break;
					    case 9:
        					month = "Ottobre";
        					break;
					    case 10:
					        month = "Novembre";
					        break;
					    case 11:
					        month = "Dicembre";
					        break;
					    
					}
            		return day + ' , ' + month;
        		}
    		},

  			startCalendar: $('#rangestart'),

  			onChange: function (date, text) {
  			$('.checkbox').find('.active_date').prop("checked",true);
			$('.checkbox').find('.active_date').attr("checked",true);
     		date_end = text;
    		},

		});


	//funzione per la scrittura delle eccezioni
	$("#salva").on('click',function(){


		if (ExceptionID==0) {
			
			modify=0;
			
		} else {
			
			modify=1;
		}
		

		if((date_end=="" || date_start=="") && $('.checkbox').find('.active_date').is(":checked")){
			$('.checkbox').find('.active_date').attr("checked",false);
	       	$('.checkbox').find('.active_date').prop("checked",false);
			alert("Devi inserire entrambe le date");
		}

		for (y=0;y<=giorno;y++) {
			
			for (x=0;x<=ore;x++) {		
				
				if (eccezione[y][x]==0) {
				
					flag=1;
				
				} else {
				
					flag=0;	
					break;
				}	
			}

			if (flag==0) {
			
			break;
			}
		}
		
		
		if(flag==1){
			alert("Devi inserire almeno un eccezione oraria");	
		}

		$.ajax({	
			type: "POST",
			url: "Controllo_date.php",
			data: { ID_song: ID_song, date_start: date_start, date_end: date_end,modify: modify,ExceptionID: ExceptionID},
			success: function(response){
				control=response;
				

			if(flag==0 && control==0){
			$.ajax({	
				type: "POST",
				url: "scrittura_eccezioni.php",
				data: { ID_song: ID_song, date_start: date_start, date_end: date_end, eccezione: eccezione, modify: modify,ExceptionID: ExceptionID},
				success: function(result){
					
					if(modify==0){
						alert("Eccezione aggiunta con successo.");
						location.reload(true);
					}else{
						alert("Eccezione modificata con successo.");
						location.reload(true);	
					}		

				}

			});

			}else{
				alert("E' già presente un eccezione per questo intervallo.");
			}

			}
		});


	});	

	$("#annulla").on('click',function(){
	
		window.location.href = ("tracks_manager.php?global");

	});	

	$("#elimina").on('click',function(){

	var annulla = window.confirm("Sei sicuro di eliminare questa eccezione?");
     
    if (annulla) {
        $.ajax({	
				type: "POST",
				url: "elimina_eccezioni.php",
				data: {ID_song: ID_song,ExceptionID: ExceptionID},
				success: function(result){

					var res = parseInt(result, 10)

					if(res===1){
						alert("Eccezione eliminata con successo.");
						location.reload(true);
					}else{
						alert("Non puoi eliminare il default prima delle altre eccezioni.");
					}
				}

		});
    }else{
    	location.reload(true);
    }
    	
	});


	});


	</script>
	<head>
		<meta charset="UTF-8">

		<title>Radio DJ</title>
	
	</head>
	
	<body>

	<h3 class="ui header" style="margin-top:10px;margin-left:10px">
 		 <i class="unordered list icon"></i>
  			<div class="content">
    			Traccia Audio
  			</div>
	</h3>

	
	<table class="ui blue table">
	
		<tr>
			<td class="center aligned two wide">
				<i class="user large icon"></i>
			</td>	
			<td class="five wide">
				<div class="ui input focus large" style="width:400px">
		  			<input type="text" value="<?php echo Get_AT($song_ID)[0];?>" readonly/>
				</div>
			</td>
			<td class="center aligned two wide">
				<i class="music large icon"></i>
			</td>	
			<td class="five wide">
				<div class="ui input focus large" style="width:400px">
		  			<input type="text" value="<?php echo Get_AT($song_ID)[1]; ?>" readonly/>
				</div>
			</td>
		</tr>
		<tr id="infromazioni">
			<td colspan="4">
				<h4>Per inserire un eccezione con data bisogna prima inserirne una di default.</h4>
			</td>
		</tr>
		<tr>
		<td class="center aligned two wide">
		<i class="ordered list large icon select_exc"></i><label class="select_exc">Eccezioni</label>
		</td>	
		<td>
		<div class="ui input focus select_exc">
  		<select class="ui selection dropdown" id="exception" name="exception"  style="width:400px;height:45px">
  			<option value="0" selected="selected">Aggiungi...</option>
		</div>
		</td>
		
		<td class="center aligned">
			<div id="attivadata" class="ui checkbox">
	  			<input class="active_date" type="checkbox" name="active_date"><label>Attiva data</label>
	  		</div>
	  	</td>
		<td>
			<div id="datecal" class="ui form">
		    	<div class="two fields">
		    		<div class="field">
		        	<label>Data inizio</label>
		        		<div class="ui calendar" id="rangestart">
			          		<div class="ui input left icon">
			            		<i class="calendar icon"></i>
			            		<input type="text" placeholder="Start">
		          			</div>
		        		</div>
		      		</div>
		    		<div class="field">
		        	<label>Data fine</label>
		        		<div class="ui calendar" id="rangeend">
		          			<div class="ui input left icon">
		            			<i class="calendar icon"></i>
		            			<input type="text" placeholder="End">
		          			</div>
		        		</div>
		    		</div>
		    	</div>
			</div>
  		</td>
  		<td>
  		</td>
  	</tr>
  	</table>

  	<h5 style="margin-left:10px"> * Se selezionato la traccia è disabilitata per tale orario.</h5>
  
	<div class="ui top attached tabular menu">
  			<a class="item active" data-tab="lun">Lunedì</a>
  			<a class="item" data-tab="mar">Martedì</a>
  			<a class="item" data-tab="mer">Mercoledì</a>
  			<a class="item" data-tab="gio">Giovedì</a>
  			<a class="item" data-tab="ven">Venerdì</a>
  			<a class="item" data-tab="sab">Sabato</a>
  			<a class="item" data-tab="dom">Domenica</a>
  	</div>
	

	<div id="tcheckbox">
		<table class="ui blue large table">
			<tr class="center aligned">
				
	  			<td>
	  				<div class="ui checkbox">
	  				<input class="orario" type="checkbox" name="1"><label>00:00-00:59</label>
	  				</div>
		  		</td>
		  		<td>
		  			<div class="ui checkbox">
					<input class="orario"  type="checkbox" name="2"><label>01:00-01:59</label>
	  				</div>
	  			</td>
		  		<td>
		  			<div class="ui checkbox">
		  			<input class="orario" type="checkbox" name="3"><label>02:00-02:59</label>
		  			</div>
		  		</td>
		  		<td>
		  			<div class="ui checkbox">
		  			<input class="orario" type="checkbox" name="4"><label>03:00-03:59</label>
		  			</div>
		  		</td>
		  		<td>
		  			<div class="ui checkbox">
		  			<input class="orario" type="checkbox" name="5"><label>04:00-04:59</label>
		  			</div>
		  		</td>
		  		<td>
		  			<div class="ui checkbox">
		  			<input class="orario" type="checkbox" name="6"><label>05:00-05:59</label>
		  			</div>
		  		</td>
		  	</tr>
  				
  			<tr class="center aligned">
		  		<td>
		  			<div class="ui checkbox">
		  			<input class="orario" type="checkbox" name="7"><label>06:00-06:59</label>
		  			</div>
		  		</td>
		  		<td>
		  			<div class="ui checkbox">
		  			<input class="orario" type="checkbox" name="8"><label>07:00-07:59</label>
		  			</div>
		  		</td>
		  		<td>
		  			<div class="ui checkbox">
		  			<input class="orario" type="checkbox" name="9"><label>08:00-08:59</label>
		  			</div>
		  		</td>
		  		<td>
		  			<div class="ui checkbox">
		  			<input class="orario" type="checkbox" name="10"><label>09:00-09:59</label>
		  			</div>
		  		</td>
		  		<td>
		  			<div class="ui checkbox">
		  			<input class="orario" type="checkbox" name="11"><label>10:00-10:59</label>
		  			</div>
		  		</td>
		  		<td>
		  			<div class="ui checkbox">
		  			<input class="orario" type="checkbox" name="12"><label>11:00-11:59</label>
		  			</div>
		  		</td>
  				</tr>
  				
  			<tr class="center aligned">
  				<td>
		 			<div class="ui checkbox">
		  			<input  class="orario" type="checkbox" name="13"><label>12:00-12:59</label>
		  			</div>
		  		</td>
		  		<td>
		  			<div class="ui checkbox">
		  			<input class="orario" type="checkbox" name="14"><label>13:00-13:59</label>
		  			</div>
		  		</td>
				<td>
					<div class="ui checkbox">
		  			<input class="orario" type="checkbox" name="15"><label>14:00-14:59</label>
		  			</div>
		  		</td>
		  		<td>
		  			<div class="ui checkbox">
		  			<input class="orario" type="checkbox" name="16"><label>15:00-15:59</label>
		  			</div>
		  		</td>
		  		<td>
		  			<div class="ui checkbox">
		  			<input class="orario" type="checkbox" name="17"><label>16:00-16:59</label>
		  			</div>
		  		</td>
		  		<td>
		  			<div class="ui checkbox">
		  			<input class="orario" type="checkbox" name="18"><label>17:00-17:59</label>
		  			</div>
		  		</td>
		  	</tr>
  				
  			<tr class="center aligned">
		  		<td>
		  			<div class="ui checkbox">
		  			<input class="orario" type="checkbox" name="19"><label>18:00-18:59</label>
		  			</div>
		  		</td>
		  		<td>
		  			<div class="ui checkbox">
		  			<input class="orario" type="checkbox" name="20"><label>19:00-19:59</label>
		  			</div>
		  		</td>
		  		<td>
		  			<div class="ui checkbox">
		  			<input class="orario" type="checkbox" name="21"><label>20:00-20:59</label>
		  			</div>
		  		</td>
		  		<td>
		  			<div class="ui checkbox">
		  			<input class="orario" type="checkbox" name="22"><label>21:00-21:59</label>
		  			</div>
		  		</td>
		  		<td>
		  			<div class="ui checkbox">
		  			<input class="orario" type="checkbox" name="23"><label>22:00-22:59</label>
		  			</div>
		 		</td>
				<td>
	  				<div class="ui checkbox">
	  				<input class="orario" type="checkbox" name="24"><label>23:00-23:59</label>
	  				</div>
	  			</td>

			</tr>
  			<tr class="center aligned">
  				<td colspan="12">
 				<div class="small ui check button"><label>Seleziona tutto</label></div>
  				<div class="small ui uncheck button"><label>Deseleziona tutto</label></div>
  				</td>		
  			</tr>	
		</table>
	</div>

	<div class="confirm" style="margin-top:40px">
		
		<button id="annulla" class=" big right floated ui icon labeled button" style="margin-right:30px">
  		<i class="reply icon"></i><label>Chiudi</label>
		</button>
		<button id="elimina" class=" big right floated ui icon labeled negative button">
  		<i class="delete calendar icon"></i><label>Elimina</label>
		</button>
		<button id="salva" class=" big right floated ui icon labeled primary button">
  		<i class="add to calendar icon"></i><label>Aggiungi</label>
		</button>


	</div>	


	</body>
</html>
