<!DOCTYPE html>
<html>

<head>

	<title>Impostazioni</title>

	<script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
	<script type="text/javascript"  charset="utf8" src="js/datatables.min.js"></script>
	<script type="text/javascript" type="text/css" href="js/buttons.semanticui.min.css"></script>
  	<link rel="stylesheet" type="text/css" href="Semantic/semantic.min.css">
	<script src="js/semantic.min.js"></script>
	<link rel="stylesheet" type="text/css" href="js/dataTables.semanticui.min.css">


</head>
<body>

	<h3 class="ui center aligned header" style="margin-top:40px">Impostazioni</h3>

	<table id="menutable" class="ui blue large table" style="margin-top:40px">
			<tr  class="center aligned">
				<td>
					<h4>Nome del Database:</h4>
				</td>
				<td>
					<div class="ui input focus large" style="width:400px">
						<input type="text" name="nomedb">
					</div>
				</td>
			</tr>
			<tr  class="center aligned">
				<td>
					<h4>Nome Host:</h4>
				</td>
				<td>
					<div class="ui input focus large" style="width:400px">
					 	<input class="ui input focus" type="text" name="nomehost">
					</div>	
				</td>
			</tr>
			<tr  class="center aligned">
				<td>
					<h4>Username:</h4>
				</td>
				<td>
					<div class="ui input focus large" style="width:400px">
						<input class="ui input focus" type="text" name="usr">
					</div>
				</td>
			</tr>
			<tr  class="center aligned">
				<td>
					<h4>Password:</h4>
				</td>
				<td>
					<div class="ui input focus large" style="width:400px">
						<input class="ui input focus" type="text" name="pwd">
					</div>
				</td>
			</tr>
			<tr  class="center aligned">
				<td>
					<h4>Root Directory:</h4>
				</td>
				<td>
					
						<input id="directory" class="ui input focus" type="file" name="root"/>
					
				</td>
			</tr>

</body>
</html>