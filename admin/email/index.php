<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<!-- <script>

	Pusher.logToConsole = true;
	var pusher = new Pusher('73ef9c76d34ce11d7557', {
		cluster: 'ap1'
	});
	var channel = pusher.subscribe('my-channel');
	channel.bind('my-event', function(data) {
		alert(JSON.stringify(data));
	});
</script> -->
<script>
	var pusher = new Pusher('73ef9c76d34ce11d7557', {
		cluster: 'ap1'
	});
	var channel = pusher.subscribe('email');
	channel.bind('email_new_order', function(data) {
		console.log(data);
	});
</script>