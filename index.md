+++
title = "UPLOAD"
+++

<body>
	<form enctype="multipart/form-data" method="post" action="api.php?output=html">
		<p><strong>File:</strong></p>
		<input type="file" name="files[]" multiple required/>
		<p><strong>Key:</strong></p>
		<input type="password" name="key" required/>
		<input type="submit" value="Upload..."/>
	</form>
</body>


---

You can also use `curl` to upload files:

```bash
#!/bin/fish

set f '/path/to/file'
set k 'seekrit'

curl -F files[]="@$f" -F key="$k" https://punpun.xyz/upload/api.php
```

Alternativly, use [punf](https://github.com/onodera-punpun/punf).
