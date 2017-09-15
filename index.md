+++
title = "UPLOAD"
+++

<body>
	<form enctype="multipart/form-data" method="post" action="api.php?output=html">
		<p><strong>File:</strong></p>
		<input type="file" name="files[]" multiple/>
		<p><strong>ID:           Key:</strong></p>
		<input type="text" name="id" required/>
		<input type="password" name="key" required/>
		<input type="submit" name="upload" value="Upload..."/>
		</br></br></br>
		<input type="submit" name="view" value="View all uploads"/>
	</form>
</body>


---

You can also use `curl` to upload files:

```bash
#!/bin/fish

set f '/path/to/file'

set i 'DarkLord66'
set k 'seekrit'

# Upload files
curl -F files[]="@$f" -F id="$i" -F key="$k" \
	https://punpun.xyz/upload/api.php

# View list of uploaded files
```

Alternativly, use [punf](https://github.com/onodera-punpun/punf).
