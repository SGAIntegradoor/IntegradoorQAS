<?php

session_destroy();

echo '<script>
	localStorage.removeItem("initModal");
	window.location = "login";

</script>';