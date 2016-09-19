function register(){
	var username = document.getElementById("username").value;
	var pwd = document.getElementById("password").value;
	var credential = "username="+encodeURIComponent(username)+"&password=" +encodeURIComponent(pwd);
	console.log(credential);

	var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
	xmlHttp.open("POST", "register.php", true); // Starting a POST request (NEVER send passwords as GET variables!!!) send username and password to login.php
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // It's easy to forget this line for POST requests
	xmlHttp.addEventListener("load", function(){
		var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
		//can i directly send message? since i didnt do anything according to success or fail
		if(jsonData.success){  // in PHP, this was the "success" key in the associative array; in JavaScript, it's the .success property of jsonData
			alert("You have successfully registered!");
		}else{
			alert(jsonData.message);
		}
	}, false); // Bind the callback to the load event
	console.log(JSON.stringify(credential));
	xmlHttp.send(credential);	
}

function login(){
	var username = document.getElementById("username").value;
	var pwd = document.getElementById("password").value;
	var credential = "username="+encodeURIComponent(username)+"&password=" +encodeURIComponent(pwd);

	var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
	xmlHttp.open("POST", "login.php", true); // Starting a POST request (NEVER send passwords as GET variables!!!) send username and password to login.php
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // It's easy to forget this line for POST requests
	xmlHttp.addEventListener("load", function(){
		// var temp = event.target.responseText; 
		// console.log("response" + temp)
		var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
		
		if(jsonData.success){  // in PHP, this was the "success" key in the associative array; in JavaScript, it's the .success property of jsonData
			//alert(jsonData.message);
			//change the html here
			//use jQuery shit
			/*
			better design should separate the cal operation in other js
			*/
			//var d = new Date();
			//makeDays(d.getFullYear(), d.getMonth(),true);
			
			var year_old = $('#year').html();
			var month_old = $('#month').html();
			var year = parseInt(year_old);
			var month = parseInt(month_old)-1;
			//alert(year);
			//alert(month);
			makeDays(year,month,true);
			
			$("#auth_div").hide();
			//there should be some clearer way to display user name
			$("div#greet_div").empty().append("<p>Hello, "+jsonData.username+"</p>");
			// $("div#greet_div p").css('display', 'inline-block');
			$("div#greet_div").append($('<button/>',{
				"class":"button",
				"type":"button",
				"value":"logout",
				"onclick":"logout()"
			})).find("button").html("logout");
			$("div#greet_div").show(0, function(){
				//var d = new Date();
				//bindEvents(d.getFullYear(), d.getMonth());
				var year_old = $('#year').html();
			var month_old = $('#month').html();
			var year = parseInt(year_old);
			var month = parseInt(month_old)-1;
			bindEvents(year,month);
			});
		}else{
			alert(jsonData.message);
		}
	}, false); // Bind the callback to the load event
	// console.log(JSON.stringify(credential));
	xmlHttp.send(credential);	
}

function logout(){
	var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
	xmlHttp.open("GET", "logout.php", true); // Starting a POST request (NEVER send passwords as GET variables!!!) send username and password to login.php
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // It's easy to forget this line for POST requests
	xmlHttp.addEventListener("load", function(){
		// var temp = event.target.responseText; 
		// console.log("response" + temp)
		var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
		
		if(jsonData.success){  // in PHP, this was the "success" key in the associative array; in JavaScript, it's the .success property of jsonData
			alert(jsonData.message);
			/*
			better design should separate the cal operation in other js
			*/
			$("#auth_div").show();
			// $("div#greet_div > p").text("Hello, "+username)
			// $.when($("#greet_div p").remove()).then($("#greet_div").hide();	 );
			$("#greet_div p").remove()
			$("#greet_div").hide();
			clearDays();
		}else{
			alert(jsonData.message);
		}
	}, false); // Bind the callback to the load event
	// console.log(JSON.stringify(credential));
	xmlHttp.send();
}