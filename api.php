<?php

//
// Configuration.
//

// The root URL, be sure to add a trailing slash.
define(URL, "https://punpun.xyz/");

// The directory where uploaded files should be stored, be sure to add
// a trailing slash.
define(DIR, "/srv/punpun.xyz/uploads/");

// IDs and their respective keys.
// TODO: I actually want to define() this as well for consistency.
$IDS = [
	"id1" => "pass",
	"id2" => "pass",
];

//
// Functions.
//

// check_format checks if the request is a POST request, and the
// desired output format. This function can return FALSE, "plain" or
// "html".
function check_format() {
	if ($_SERVER["REQUEST_METHOD"] != "POST") {
		return FALSE;
	}

	// Check if we shoud return pretty HTML output or plain output
	// (for something like cURL).
	return isset($_GET["output"]) ? $_GET["output"] : "plain";
}

// random_string generates a random string of a given lenght.
function random_string($lenght) {
	$chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_";

	$string = "";
	for ($i = 0; $i < $lenght; $i++) {
		$string .= $chars[rand(0, strlen($chars)-1)];
	}

	return $string;
}

// generate_name generates a random, non-existing file name.
function generate_name($orig_name) {
	// Generate random name.
	$new_name = random_string(4) . "." . end((explode(".",
		$orig_name)));

	// Check if the file alread exists.
	foreach ($IDS as $id) {
		if (file_exists(DIR . $id . "/" . $new_name)) {
			return generate_name($orig_name);
		}
	}

	return $new_name;
}

// view_files returns an array with URLs of all files uploaded by a
// specific ID.
function view_files() {
	$urls = [];

	foreach (scandir(DIR . $_POST["id"]) as $file) {
		// Exclude hidden files.
		if ($file[0] == '.') {
			continue;
		}

		$urls[$file] = filemtime(DIR . $_POST["id"] . "/" . $file);
	}

	asort($urls);
	$urls = array_reverse(array_keys($urls));

	return $urls;
}

// upload_files uploads files and returns an array with URLs of the
// uploaded files.
function upload_files() {
	$urls = [];

	foreach ($_FILES["files"]["error"] as $key => $error) {
		if ($error == UPLOAD_ERR_OK) {
			$orig_name = $_FILES["files"]["name"][$key];
			$tmp_name = $_FILES["files"]["tmp_name"][$key];

			$new_name = generate_name($orig_name);

			if (move_uploaded_file($tmp_name, DIR . $_POST["id"] .
				"/" . $new_name)) {
				$urls[] = URL . $new_name;
			}
		}
	}

	return $urls;
}


//
// Main.
//

$valid_key = $IDS[$_POST["id"]] == $_POST["key"];
$format = check_format();

if ($format == "html") {
	include "header.html";
	if ($valid_key) {
		$urls = isset($_POST["view"]) ? view_files() : upload_files();

		foreach ($urls as $url) {
			echo "			<li><a href=\"" . $url .
				"\">$url</a></li>\n";
		}
	} else {
		echo "<h3>Invalid ID or key!</h3>\n";
	}
	include "footer.html";
} elseif ($format == "plain") {
	if ($valid_key) {
		$urls = isset($_POST["view"]) ? view_files() : upload_files();

		foreach ($urls as $url) {
			echo $url . "\n";
		}
	} else {
		echo "Invalid ID or key!\n";
	}
}
