<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Document</title>
	</head>
	<body>
        {{-- <script src="plugins/pspdfkit/pspdfkit.js"></script> --}}

        <script src="https://cdn.jsdelivr.net/npm/pspdfkit@2023.4.0/dist/pspdfkit.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pspdfkit@2023.1.4/dist/pspdfkit-lib/pspdfkit-2023.1.4.min.css">

        <div id="pspdfkit" style="height: 100vh"></div>

        <script>
            PSPDFKit.load({
                container: "#pspdfkit",
                document: "<?= asset('/uploads/publikasi/pp-nomor-79-tahun-2014-kebijakan-energi-nasional.pdf')?>" // Add the path to your document here.
            })
            .then(function(instance) {
                console.log("PSPDFKit loaded", instance);
            })
            .catch(function(error) {
                console.error(error.message);
            });
        </script>
    </body>
</html>