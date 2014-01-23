<?
	require_once('db.class.php');
	$benutzer = "%".$_SERVER['REMOTE_USER']."%";
	$sql = 'SELECT * from film_benutzer fb where fb.kurz like :usernr';
	$stmt = $db->prepare($sql);
	$stmt->bindParam('usernr',$benutzer,PDO::PARAM_STR);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	$benutzer = $result['id'];

	/*
	http://aspirine.org/htpasswd_en.html
	*/
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-15" />

<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script type="text/javascript" src="jquery.flip.js"></script>

<style>
body {background: black; font-family: Arial; font-size: 13px; color: white;}
.mov { float: left; width: 200px;z-index: 1; margin-right: 5px}
.mov .poster {height: 300px; overflow: hidden; position: relative;cursor: pointer;}
.mov .poster img { width: 200px;}
.mov .poster .rating { position: absolute; top:5px; left:5px; background: #ff900e; color:white;width: 25px; text-align: center; padding: 3px}
.mov .poster .ang_icon { background: url('angeschaut_g.png') no-repeat; width: 103px; height:104px; position: absolute; right: -6px; top:-6px;}
.mov .poster .kopier_icon { background: url('kopierliste.png') no-repeat; width: 103px; height:104px; position: absolute; left: -7px; bottom:-7px;}
.mov .name { height: 55px;margin-top:5px;padding:5px;}
#out { padding-top:20px; }
.mov .update { background: url('update.png') no-repeat grey; padding:10px; font-size: 50px;height: 280px; color:#cdcdcd;text-shadow: 2px 1px 7px #000;line-height: 60px}
.plus {position: absolute; bottom:-30px; right:20px; width: 30px; height: 30px; background: url(plus.png); cursor: pointer}
.minus {position: absolute; bottom:-30px; right:20px; width: 30px; height: 30px; background: url(minus.png); cursor: pointer}
.plot {color: black; padding: 10px; line-height: 20px; cursor: pointer}
.row { position: relative;margin:0px auto;width: 92%; }
.leihliste { width: 350px; position: absolute; left:-320px;position: fixed; z-index: 2; top:10px;}
.leihliste .liste { width: 298px; background: black; border:1px solid grey; left:0px;padding:10px; line-height: 25px; height: 300px; overflow-y: scroll;}
.leihliste h1 { font-size: 20px}
#loader {background: url(loader.gif) center top no-repeat; height: 64px; margin-top: 50px}
.leihliste .icon { width: 30px; height:30px; background: url(button.png) no-repeat; position: absolute;right:0px; cursor: pointer}
.leihliste .liste .kopier .ang_icon { background: url('angeschaut_n.png') no-repeat; width: 15px; height:15px; position: absolute; right: 0px; top:3px; cursor: pointer}
.leihliste .liste .kopier .del {width: 30px; height:30px; position: absolute; right: 30px;top:3px;}
.leihliste .liste .kopier { position: relative;}
.leihliste .angeschaut { width: 298px; background: black; border:1px solid grey; left:0px;padding:10px; margin-top: 10px; line-height: 25px; height: 500px; overflow-y: scroll;}
</style>

<script>

var googleResp;
var current_user = <?=$benutzer?>;

$(function() 
{

	initFilmListe();
	getLeihliste();

	var out = false;

	$('.leihliste .icon').on('click', function() { 
		if(!out)
		{
			$('.leihliste').animate( {left:0}, 500, function() {});
			out = true;
		}
		else
		{
			$('.leihliste').animate( {left:-320}, 500, function() {});
			out = false;
		}
	});
});


function initFilmListe()
{
	var jqxhr = $.getJSON( "load.php?benutzer="+current_user )
		.done(function(data) {
			googleResp = data;
			writeInital();

			$('.poster').on( 'click', '.plus', function(event) {event.preventDefault(); event.stopPropagation(); addToLeihliste($(this).parent().data('filmnr')); $(this).removeClass('plus').addClass('minus');  });
			$('.poster').on( 'click', '.minus', function(event) {event.preventDefault();  event.stopPropagation(); removeFromLeihliste($(this).parent().data('filmnr')); $(this).removeClass('minus').addClass('plus'); });
			$('.poster').on( 'click', '.kopier_icon', function(event) {event.preventDefault();  event.stopPropagation(); addToWatchlist($(this).parent().data('filmnr')); });

			
			$('.poster').hover(function() { $(this).find('.icon').animate({ bottom: 10}, 50, function() {})}, function() { $(this).find('.icon').animate({ bottom: -30}, 50, function() {})});


			$('.poster').click(function () 
		    {
		    	
		    	if($(this).data('side') != 'backside')
		        {
		          $(this).flip({ direction: 'rl', speed: 350, color: '#ffffff',content : '<div class="plot">'+findPlot($(this).data('filmnr'))+'</div>' });
		          $(this).data('side', 'backside');
		        }
		        else
		        {
		          $(this).revertFlip();
		          $(this).data('side', 'frontside');
		        }
		    });

		})
		.fail(function() {
			console.log( "error1" );
		});
}

function findPlot(filmnr)
{
	for(var index in googleResp)
	{
		if(googleResp[index].filmnr == filmnr)
			return googleResp[index].plot;
	}

	return '';
}

function getLeihliste()
{
	var jqxhr = $.getJSON( 'ajax.php?action=leihliste&benutzer='+current_user )
	.done(function(data) {
		var out = '';

		for(var index in data[0])
		{
				
			if(data[0][index].angeschaut == '1')
			{
				$('.leihliste .angeschaut').append('<div class="angItem" data-filmnr="'+data[0][index].id+'">'+data[0][index].name+'</div>');			
			}
			else
			{
				$('.leihliste .liste').append('<div class="kopier" data-filmnr="'+data[0][index].id+'">'+data[0][index].name+' <div class="del"></div><div class="ang_icon" title="angeschaut"></div></div>');
			}
		}

		$('.leihliste .liste').on( 'click', '.ang_icon', function() {  addToWatchlist($(this).parent().data('filmnr')); });

	})
	.fail(function() {
		console.log( "error2" );
	});
}

function writeInital()
{
	var outstr = '';
	var date = 0;// googleResp[0].date;

	for(var index in googleResp)
	{
		var plus = 'plus';
		var kopiericon = '';

		if(googleResp[index].aufliste > 0)
		{
			plus = 'minus';
			kopiericon = '<div class="kopier_icon"></div>';
		}

		var angeschaut = '<div class="ang_icon" title="angeschaut"></div>';

		

		var icon = '<div class="icon '+ plus +'"></div>';

		if(googleResp[index].angeschaut != '1') 
		{
			angeschaut = ''; 
		}
		else 
		{
			icon = ''
			kopiericon = '';
		}

		outstr += '<div class="mov"><div class="poster" data-filmnr="'+googleResp[index].filmnr+'"><img src="poster/'+googleResp[index].filmnr+'.jpg" />'+icon+'<div class="rating">'+googleResp[index].rating+'</div>'+angeschaut+kopiericon+'</div><div class="name">'+googleResp[index].name+'</div></div>';
		
		/*
		if( typeof googleResp[parseInt(index)+1] === 'undefined' || googleResp[index].date != date )
		{
			outstr += '<div class="mov"><div class="update">update '+googleResp[index].date+'</div><div style="height:60px"></div></div>';
		} 
		*/
		
		date = googleResp[index].date;
	}
	$('#loader').remove();
	$('#out').html(outstr);
}

function addToLeihliste(filmNr)
{
	var jqxhr = $.ajax( "ajax.php?action=add&type=mysql&filmNr="+filmNr+"&benutzer="+current_user )
		.done(function(data) {
			var name = $('.poster[data-filmnr='+filmNr+']').parent().find('.name').text();
			$('.leihliste .liste').append('<div class="kopier" data-filmnr="'+filmNr+'">'+name+' <div class="del"></div><div class="ang_icon"></div></div></div>');
			$('.mov .poster[data-filmnr='+filmNr+']').prepend('<div class="kopier_icon" title="angeschaut"></div>');

		})
		.fail(function() {
		alert( "error" );
		});

	jqxhr = $.ajax( "ajax.php?action=add&type=gsheet&filmNr="+filmNr+"&benutzer="+current_user ).done(function(data) {}).fail(function() { alert( "error" ); });
		
}

function addToWatchlist(filmNr)
{
	var jqxhr = $.ajax( "ajax.php?action=angeschaut&type=mysql&filmNr="+filmNr+"&benutzer="+current_user )
		.done(function(data) {
		
			var name = $('.leihliste .kopier[data-filmnr='+filmNr+']').text();
			$('.leihliste .kopier[data-filmnr='+filmNr+']').remove();
			$('.angeschaut').append('<div class="angItem" data-filmnr="'+filmNr+'">'+name+'</div>');
			$('.mov .poster[data-filmnr='+filmNr+']').prepend('<div class="ang_icon" title="angeschaut"></div>');
			$('.mov .poster[data-filmnr='+filmNr+'] .icon').remove();
			$('.mov .poster[data-filmnr='+filmNr+'] .kopier_icon').remove();
		})
		.fail(function() {
		alert( "error" );
		});

		jqxhr = $.ajax( "ajax.php?action=angeschaut&type=gsheet&filmNr="+filmNr+"&benutzer="+current_user ).done(function(data) {}).fail(function() { alert( "error" ); });
}

function removeFromLeihliste(filmNr)
{
	var jqxhr = $.ajax( "ajax.php?action=remove&type=mysql&filmNr="+filmNr+"&benutzer="+current_user )
		.done(function(data) {

		$('.leihliste .kopier[data-filmnr='+filmNr+']').remove();
		$('.mov .poster[data-filmnr='+filmNr+'] .kopier_icon').remove();
		//alert( data);
		})
		.fail(function() {
		alert( "error" );
		});

		jqxhr = $.ajax( "ajax.php?action=remove&type=gsheet&filmNr="+filmNr+"&benutzer="+current_user ).done(function(data) {}).fail(function() { alert( "error" ); });

}

</script>
</head>

<body>
<div class="row">
<div class="leihliste">
	<div class="icon"></div>
	<div class="liste"><h1>Meine Kopierliste</h1></div>
	<div class="angeschaut"><h1>Angeschaut</h1></div>
</div>
<div id="loader"></div>
<div id="out"></div>
</div>
</body>
</html>