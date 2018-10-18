(function () {
	const player = document.getElementById('player');
	const canvas = document.getElementById('canvas');
	const context = canvas.getContext('2d');
	const captureButton = document.getElementById('capture');
	const sendForm = document.getElementById('sendForm1');
	
	const constraints = {
		video: true,
	};
	
	function loadFrame(frame) {
		console.log(frame);
		var xhr = new XMLHttpRequest();
		xhr.open('POST', 'upload.php', true)
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.addEventListener('readystatechange', function() {
			if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
				// console.log(request.responseText);
				document.getElementById('frame-ajax').innerHTML = '<img id=frame-over src="inc/filter/frame' + frame + '.png" />';
			}
		});
		xhr.send(null);	
	}

	(function() {
    var inputs = document.getElementsByClassName('radio-frame'),
        inputsLen = inputs.length;

    for (var i = 0; i < inputsLen; i++) {
        inputs[i].addEventListener('click', function() {
            loadFrame(this.value);
        });
    }
})();

	context.translate(canvas.width, 0);
	context.scale(-1, 1);

	captureButton.addEventListener('click', () => {
		// Draw the video frame to the canvas.
		canvas.style.display = "block";
		player.style.display = "none";
		sendForm.style.display = "block";
		captureButton.style.display = "none";
		document.getElementById('reload').style.display = "block";
		// document.getElementsByClassName('radio-frame').style.display = "none";
		context.drawImage(player, 0, 0, canvas.width, canvas.height);
	});
	
	// Attach the video stream to the video element and autoplay.
	navigator.mediaDevices.getUserMedia(constraints)
	.then((stream) => {
		player.srcObject = stream;
	});

	// console.log(sendForm);

	sendForm.addEventListener('click', function() {
		image = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");
		console.log(image);
		document.getElementById('hidden_data').value = image;
	})

})();
