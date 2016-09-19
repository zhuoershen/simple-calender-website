<?php
if (session_status() == PHP_SESSION_NONE) {
	ini_set("session.cookie_httponly",1);
    session_start();
}
if (empty($_SESSION) or !array_key_exists("username", $_SESSION)){
	// echo '<script type="text/javascript">var username='.$_SESSION['username'].';var logined=true;';
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Cynic, the unkown calendar on the internet</title>
	<meta charset="UTF-8"/>
	<link rel="stylesheet" type="text/css" href="main.css" />
	<p>test</p>
</head>
<body>
	<!-- header -->
	<div id="header" role="banner">
		<div id="header_bottom_left">
			<a href="./" id="header_img" class="default_header" title>
				<img src="img/header_icon.png" alt="cynic" id="banner_img" >
			</a>
		</div>
	</div>

	<!-- top div for login and reg -->
	<div id="top">
		<?php if (!empty($_SESSION) and array_key_exists("username", $_SESSION)) { echo '<div id="auth_div" style="display: none">'; }
			else { echo '<div id="auth_div" ';} ?>
			<form id="auth_form" id="auth">
				<input type="text" id="username" placeholder="username" required>
				<input type="password" id="password" placeholder="password" required>
				<button class="button" type="button" value="login" onclick="login()">login</button>
				<button class="button" type="button" value="register" onclick="register()">register</button>
				<button class="button" type="button" value="logout" onclick="logout()">logout</button>
			</form>

		</div>
		<?php if (empty($_SESSION) or !array_key_exists("username", $_SESSION)) { echo '<div id="greet_div" style="display: none">'; }
		else { echo '<div id="greet_div">';echo '<p>Hello, '.$_SESSION['username'].'</p>';} ?>
			
		<?php
			echo '<button class="button" type="button" value="logout" onclick="logout()">logout</button>';
			echo "</div>";			
		?>
	</div>

	<br><br><hr>
	<!-- main content -->
	<div id="month_year"></div>
	<input type="submit" value="previous month" id="prev"/>
	<input type="submit" value="next month" id="next"/>
	
	<div class="mv-event-container" id="mvEventContainer">
		<table class="event-table">
			<thead>
			<tr>
			<!--print sun,mon...-->
			<?php
			$days = array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");
			foreach ($days as $value) {
				echo '<th>'.$value.'</th>';
			}
			?>
			</tr>
			</thead>
			<tbody>
			<!--print date-->
			<?php for ($i=0; $i < 5; $i++) { ?>
			<tr>
				<?php
				for ($j=1; $j <= 7; $j++) { 
					echo '<td></td>';
				}
				?>
			</tr>
			<?php } ?>
			</tbody>
		</table>
	</div> 
	
    <div id="creat-event-form"></div>
	<div id="add_event"></div>
<!-- <script type="text/javascript" src="js/twitter.js"></script> -->
<script type="text/javascript" src="js/jquery-1.12.2.min.js" ></script>
<link rel="stylesheet" href="jquery_ui/jquery-ui.min.css">

<script src="jquery_ui/jquery-ui.min.js"></script>

<!-- <script src="jquery_ui/external/jquery/jquery.js"></script> -->

<script type="text/javascript" src="js/authentication.js"></script>
<script type="text/javascript" src="js/buildCal.js"></script>
<script type="text/javascript">
	//$('.content').ready(makeDays(2016,2));
	
	//var d = new Date();
	//var d = $.now();
	$('.content').ready(function(){
		var year = parseInt(2016);
		var month = parseInt(3);
		makeDays(year,month);
		document.getElementById("month_year").innerHTML="<div id='year' value='"+year+"'>"+year+"</div>";
		document.getElementById("month_year").innerHTML+="<div id='month' value='"+month+"'>"+month+"</div>";
	});
	
	$(document).on('click', '#prev',function(){
		var year_old = $('#year').html();
		var month_old = $('#month').html();
		//alert(year_old);
		var year = parseInt(year_old);
		var month = parseInt(month_old)-1;
		if (month==0) {
            var month = parseInt(12);
			var year = parseInt(year-1);
        }
		makeDays(year,month);
		document.getElementById("month_year").innerHTML="<div id='year' value='"+year+"'>"+year+"</div>";
		document.getElementById("month_year").innerHTML+="<div id='month' value='"+month+"'>"+month+"</div>";
		login();
	});
	
	$(document).on('click', '#next',function(){
		var year_old = $('#year').html();
		var month_old = $('#month').html();
		//alert(year_old);
		var year = parseInt(year_old);
		var month = parseInt(month_old)+1;
		if (month==13) {
            var month = parseInt(1);
			var year = parseInt(year+1);
        }
		makeDays(year,month);
		document.getElementById("month_year").innerHTML="<div id='year' value='"+year+"'>"+year+"</div>";
		document.getElementById("month_year").innerHTML+="<div id='month' value='"+month+"'>"+month+"</div>";
		login();
	});
	//add event
	$(document).on('click', '.day-events-container', function()  {
		//alert("1");
		//var timestamp = $.now();
		var timestamp = parseInt(this.id);
		var d1 = new Date(timestamp);

		var da = d1.getDate();       //day
		var mon = d1.getMonth() + 1; //month
		var yr = d1.getFullYear();
		var date = mon+"/"+da+"/"+yr;
		
		var username = document.getElementById("username").value;
		document.getElementById("add_event").innerHTML="<div>Add Event</div> <div><label for='name'>Event Name:</label><input type='text' id='name'/></div>";
		document.getElementById("add_event").innerHTML+="<div>Event Date: "+date+"</div>";
		document.getElementById("add_event").innerHTML+="<div>Event Time: <input type='time' id='time'/></div>";
		document.getElementById("add_event").innerHTML+="<div>Event Detail:</div> <div><textarea type='text' id='detail' cols='10' rows='10'></textarea></div>";
		document.getElementById("add_event").innerHTML+="<div><input type='submit' value='Submit' id='button'/></div>";
		$("#button").click(function(event){
			//alert(d1);
			
			//prepare for the parameters
			var event_name = document.getElementById("name").value;
			var event_time = document.getElementById("time").value;
			var event_detail = document.getElementById("detail").value;
			var credential = "name="+encodeURIComponent(event_name)+"&time=" +encodeURIComponent(event_time)+"&detail="+encodeURIComponent(event_detail)+"&username="+encodeURIComponent(username)+"&timestamp="+encodeURIComponent(date);
			console.log(credential);
			
			//connect the php
			var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
			xmlHttp.open("POST", "addEvent.php", true); // Starting a POST request (NEVER send passwords as GET variables!!!) send username and password to login.php
			xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // It's easy to forget this line for POST requests
			xmlHttp.addEventListener("load", check, false); // Bind the callback to the load event
			console.log(JSON.stringify(credential));
			xmlHttp.send(credential);
		});
		
		function check(event) {//callback function
			var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
		
			if(jsonData.success){  // in PHP, this was the "success" key in the associative array; in JavaScript, it's the .success property of jsonData
				console.log(event.target.responseText);
				alert("You have added a new event successfully!");
				var d = new Date();
				bindEvents(d.getFullYear(), d.getMonth());
			
			}else{
				alert(jsonData.message);
			}
		
		//alert(d1);
    };
	
});
	
	
	
	
	
	
	
	
	
	
	
</script>

</body>
</html>

















