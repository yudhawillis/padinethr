// Pure Chat JS
(function () {
		   var done = false;					
		   var script = document.createElement('script');					
		   script.async = true;					
		   script.type = 'text/javascript';					
		   script.src = 'https://app.purechat.com/VisitorWidget/WidgetScript';					
		   		document.getElementsByTagName('HEAD').item(0).appendChild(script);					
		   script.onreadystatechange = script.onload = function (e) {						
		   		if (!done && (!this.readyState || this.readyState == 'loaded' || this.readyState == 'complete')) {							
		   		var w = new PCWidget({ c: '9173f8db-33cc-4a61-af90-8fb26de4633c', f: true });							
		   		done = true;						
		   			}					
		   		};				
		   })();
