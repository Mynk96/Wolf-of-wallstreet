
<!DOCTYPE html>
<html>
	<head>
		<title>Christmas Countdown</title>
		<style type="text/css">
        .countdownContainer{
			position: absolute;;
			top: 50%;
			left: 50%;
			transform : translateX(-50%) translateY(-50%);
			text-align: center;
			background: #179B77;
			border: 1px solid #999;
			padding: 10px;
			box-shadow: 0 0 5px 3px #ccc;
            font-size: 14px;
		}
            
            #countdownContainer{
    font-family: sans-serif;
    color: #fff;
    display: inline-block;
    font-weight: 100;
    text-align: center;
    font-size: 30px;
  }

  #countdownContainer > div{
    padding: 10px;
    border-radius: 3px;
    background: #00BF96;
    display: inline-block;
  }

  #countdownContainer div > span{
    padding: 15px;
    border-radius: 3px;
    background: #00816A;
    display: inline-block;
  }

  .smalltext{
    padding-top: 5px;
    font-size: 16px;
  }
		

			
		</style>
	</head>
	<body>
		<div id="countdownContainer">
				<div>
                <span id="days">120</span>
				    <div class = "smalltext">Days</div>
                </div>
                <div>
                    <span id="hours">4</span>
                    <div class = "smalltext">Hours</div>
                </div>
                <div>
                    <span id="minutes">12</span>
                    <div class = "smalltext">Minutes</div>
                </div>
				
				<div>
                    <span id="seconds">22</span>
                    <div class = "smalltext">Seconds</div>
                </div>
			</div>
		<script type="text/javascript">

			function countdown(){
				var now = new Date();
				var eventDate = new Date(2016, 10, 6,13,00,00);

				var currentTiime = now.getTime();
				var eventTime = eventDate.getTime();

				var remTime = eventTime - currentTiime;

				var s = Math.floor(remTime / 1000);
				var m = Math.floor(s / 60);
				var h = Math.floor(m / 60);
				var d = Math.floor(h / 24);

				h %= 24;
				m %= 60;
				s %= 60;

				h = (h < 10) ? "0" + h : h;
				m = (m < 10) ? "0" + m : m;
				s = (s < 10) ? "0" + s : s;

				document.getElementById("days").textContent = d;
				document.getElementById("days").innerText = d;

				document.getElementById("hours").textContent = h;
				document.getElementById("minutes").textContent = m;
				document.getElementById("seconds").textContent = s;

				setTimeout(countdown, 1000);
			}

			countdown();
		</script>
	</body>
</html>
