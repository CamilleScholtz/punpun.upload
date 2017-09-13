<?php
	//
	// Configuration.
	//

	// The root URL, be sure to add a trailing slash.
	define(URL, "https://punpun.xyz/");

	// The directory where uploaded files should be stored, be sure to
	// add a trailing slash.
	define(DIR, "/srv/punpun.xyz/uploads/");

	// The seekrit key.
	define(KEY, "seekrit");


	//
	// Functions.
	//

	// random_string generates a random string of a given lenght.
	function random_string() {
		$chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_";

		$string = "";
		for ($i = 0; $i < 4; $i++) {
			// TODO: Should I use -1 here?
			$string .= $chars[rand(0, strlen($chars))];
		}

		return $string;
	}

	// check_method checks if the request is a POST request, and the
	// desired output. This function can return FALSE, "plain" or
	// "html".
	function check_method() {
		if ($_SERVER["REQUEST_METHOD"] != "POST") {
			return FALSE;
		}

		// Check if we shoud return pretty html output or plain output
		// (for something like cURL).
		return isset($_GET["output"]) ? $_GET["output"] : "plain";
	}

	// upload_files uploads files and returns a list with URLs of the
	// uploaded files.
	function upload_files() {
		$urls = [];

		foreach ($_FILES["files"]["error"] as $key => $error) {
			if ($error == UPLOAD_ERR_OK) {
				$orig_name = $_FILES["files"]["name"][$key];
				$temp_name = $_FILES["files"]["tmp_name"][$key];

				// Generate a non-existing random new name.
				while (file_exists(DIR . $new_name)) {
					$new_name = random_string() . "." . end((explode(
						".", $orig_name)));
				}

				if (move_uploaded_file($temp_name, DIR . $new_name)) {
					$urls[] = URL . $new_name;
				}
			}
		}

		return $urls;
	}


	//
	// Main.
	//

	$method = check_method();
	if (!$method) {
		exit();
	}

	if ($method == "html") {
		include "header.html";
	}

	// Validate key.
	if ($_POST["key"] != KEY) {
		if ($method == "html") {
			exit("<h3>Invalid key!</h3>\n");
		} else {
			exit("Invalid key!\n");
		}
	}

	foreach (upload_files() as $url) {
		if ($method == "html") {
			echo "			<li><a href=\"$url\">$url</a></li>\n";
		} else {
			echo "$url\n";
		}
	}

	if ($method == "html") {
		include "footer.html";
	}
?>
