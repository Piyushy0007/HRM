

<?php  
 echo "test api";
 echo "</br>";
//echo $url = $_SERVER['HTTP_HOST'];
 echo '<input type ="button" onclick="test_block_origin()" value="button"> ';
?>
<script>
	function test_block_origin(){
		var xhttp = new XMLHttpRequest();

		
		 
		  xhttp.onreadystatechange = function() {
		    if (xhttp.readyState == 4 && xhttp.status == 200) {
		      document.getElementById("demo").innerHTML = xhttp.responseText;
		    }
		  };

		  xhttp.open("GET", "http://127.0.0.1:8000/api/positions", true);

		  xhttp.setRequestHeader("Authorization", "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hZG1pbmxvZ2luIiwiaWF0IjoxNjE3ODgyOTU2LCJleHAiOjE2MTc4ODY1NTYsIm5iZiI6MTYxNzg4Mjk1NiwianRpIjoiaWhvNHkyc25uNFlmWTJzYiIsInN1YiI6MSwicHJ2IjoiY2YyODRjMmIxZTA2ZjMzYzI2YmQ1Nzk3NTY2ZDlmZDc0YmUxMWJmNSJ9.rY74T_OeP0BLBqOZZAw_ajnn6aFuNlhwqm4aDOiVAdo")
		  xhttp.setRequestHeader("Content-Type", "application/json");
		//   xhttp.setRequestHeader("Content-length", "54138");
		  xhttp.setRequestHeader("Accept", "application/json");
  		xhttp.send();
	}
</script>