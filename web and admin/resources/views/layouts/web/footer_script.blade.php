<script src="{{ URL::asset('assets/web/js/popper.min.js')}}"></script>
<script src="{{ URL::asset('assets/web/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ URL::asset('assets/web/js/owl.carousel.js')}}"></script>
<script src="{{ URL::asset('assets/web/js/custom.js?v='.rand(0,99999999))}}"></script>
<script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
<script>
  if ('loading' in HTMLImageElement.prototype) {
    const images = document.querySelectorAll('img[class="lazyload"]');
    images.forEach(img => {
      img.src = img.src;
    });
  } else {
    // Dynamically import the LazySizes library
    const script = document.createElement('script');
    script.src =
      'https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.1.2/lazysizes.min.js';
    document.body.appendChild(script);
  }
</script>
<script>

	// var x = document.getElementById("demo");
	$(document).ready(function() {
		getLocation();

	});





	function getLocation() {
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(showPosition);
		} else {
			x.innerHTML = "Geolocation is not supported by this browser.";
		}
	}

	function showPosition(position) {
		console.log(position);
		//   $('#latitude').val(position.coords.latitude);
		//   $('#longitude').val(position.coords.longitude);
		getHomeDataByLocation(position.coords.latitude, position.coords.longitude);
		// x.innerHTML = "Latitude: " + position.coords.latitude + 
		// "<br>Longitude: " + position.coords.longitude;
	}

	async function getHomeDataByLocation(lat = null, long = null) {
		var latitude = lat;
		var longitude = long;
		await setCookie('lat', lat, 1);
		await setCookie('long', long, 1);
	}

	function setCookie(cname, cvalue, exdays) {
		console.log(cname, cvalue);
		// document.cookie = cname + "=''";
		const d = new Date();
		d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
		let expires = "expires=" + d.toUTCString();
		document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}

	function getCookie(cname) {
		let name = cname + "=";
		let decodedCookie = decodeURIComponent(document.cookie);
		let ca = decodedCookie.split(';');
		for (let i = 0; i < ca.length; i++) {
			let c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
			}
		}
		return "";
	}
</script>