@extends('auth.main')

@section('head')
    <link rel="stylesheet" href="{{ asset('assets/css/certificateFonts.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
@endsection

@section('contents')
    <h1 class="text" style="font-family: Great Vibes">Hello</h1>
    <h1 class="text" style="font-family: Lora">Hello</h1>
    <canvas id="certificateCanvas" width="1000" height="600"></canvas>
    <button type="button" id="downloadBtn" class="btn btn-success">Download Certificate</button>
@endsection

@section('script')
    <script>
        function wrapText(context, text, x, y, maxWidth, lineHeight) {
            var words = text.split(' ');
            var line = '';
            var lines = [];

            for (var n = 0; n < words.length; n++) {
                var testLine = line + words[n] + ' ';
                var metrics = context.measureText(testLine);
                var testWidth = metrics.width;

                if (testWidth > maxWidth && n > 0) {
                    lines.push(line);
                    line = words[n] + ' ';
                } else {
                    line = testLine;
                }
            }
            lines.push(line);

            for (var i = 0; i < lines.length; i++) {
                context.fillText(lines[i], x, y + (i * lineHeight));
            }
        }

        function capitalizeFirstLetterOfEachWord(text) {
            return text
                .split(' ')
                .map(word =>
                    word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()
                )
                .join(' ');
        }

        $(document).ready(function() {
            var canvas = document.getElementById('certificateCanvas');
            var context = canvas.getContext('2d');
            var img = new Image();
            var signatureImgLeft = new Image();
            var signatureImgRight = new Image();
            var signatureLoadedLeft = false;
            var signatureLoadedRight = false;
            var signatureCanvasLeft = document.createElement('canvas');
            var signatureCanvasRight = document.createElement('canvas');
            var signatureHeight = canvas.height / 2 + 135;
            var signaturePositionLeft = {
                x: (canvas.width / 2 - 230),
                y: signatureHeight,
                width: 150,
                height: 75
            };
            var signaturePositionRight = {
                x: (canvas.width / 2 + 90),
                y: signatureHeight,
                width: 150,
                height: 75
            };

            img.onload = function() {
                context.drawImage(img, 0, 0, canvas.width, canvas.height);
                document.fonts.ready.then(function() {
                    drawCertificate();
                });
            };
            img.src = '{{ asset('assets/img/certificate.png') }}';

            var signatureLeftSrc = '{{ asset($director->employee_signature_img) }}';
            var signatureRightSrc = '{{ asset($teacher->employee_signature_img) }}';

            signatureImgLeft.onload = function() {
                signatureCanvasLeft.width = signatureImgLeft.width;
                signatureCanvasLeft.height = signatureImgLeft.height;
                var tempContext = signatureCanvasLeft.getContext('2d');
                tempContext.drawImage(signatureImgLeft, 0, 0);

                var imageData = tempContext.getImageData(0, 0, signatureCanvasLeft.width,
                    signatureCanvasLeft.height);
                var data = imageData.data;

                for (var i = 0; i < data.length; i += 4) {
                    var r = data[i];
                    var g = data[i + 1];
                    var b = data[i + 2];

                    if (r > 200 && g > 200 && b > 200) {
                        data[i + 3] = 0;
                    }
                }

                tempContext.putImageData(imageData, 0, 0);
                signatureLoadedLeft = true;
                context.drawImage(signatureCanvasLeft, signaturePositionLeft.x,
                    signaturePositionLeft.y, signaturePositionLeft.width,
                    signaturePositionLeft.height);
            };
            signatureImgLeft.src = signatureLeftSrc;

            signatureImgRight.onload = function() {
                signatureCanvasRight.width = signatureImgRight.width;
                signatureCanvasRight.height = signatureImgRight.height;
                var tempContext = signatureCanvasRight.getContext('2d');
                tempContext.drawImage(signatureImgRight, 0, 0);

                var imageData = tempContext.getImageData(0, 0, signatureCanvasRight.width,
                    signatureCanvasRight.height);
                var data = imageData.data;

                for (var i = 0; i < data.length; i += 4) {
                    var r = data[i];
                    var g = data[i + 1];
                    var b = data[i + 2];

                    if (r > 200 && g > 200 && b > 200) {
                        data[i + 3] = 0;
                    }
                }

                tempContext.putImageData(imageData, 0, 0);
                signatureLoadedRight = true;
                context.drawImage(signatureCanvasRight, signaturePositionRight.x,
                    signaturePositionRight.y, signaturePositionRight.width,
                    signaturePositionRight.height);
            };
            signatureImgRight.src = signatureRightSrc;

            function drawCertificate() {
                context.clearRect(0, 0, canvas.width, canvas.height);
                context.drawImage(img, 0, 0, canvas.width, canvas.height);

                context.fillStyle = "black";
                context.textAlign = "center";

                var name = capitalizeFirstLetterOfEachWord(
                    '{{ $employee->last_name . ' ' . $employee->first_name }}');
                context.font = "46.7px 'Great Vibes', cursive";
                context.fillText(name, canvas.width / 2, (canvas.height / 2) + 40);

                var description =
                    'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.';
                context.font = "13px 'Lora', serif";
                var maxWidth = canvas.width - 400;
                var lineHeight = 16;
                wrapText(context, description, canvas.width / 2, canvas.height / 2 + 70, maxWidth, lineHeight);

                var namedescription = 'of internship'.toUpperCase();
                context.font = "bold 34px 'Lora', serif";
                wrapText(context, namedescription, canvas.width / 2, canvas.height / 2 - 85, maxWidth, lineHeight);

                if (signatureLoadedLeft) {
                    context.drawImage(signatureCanvasLeft, signaturePositionLeft.x, signaturePositionLeft.y,
                        signaturePositionLeft.width, signaturePositionLeft.height);
                }

                if (signatureLoadedRight) {
                    context.drawImage(signatureCanvasRight, signaturePositionRight.x, signaturePositionRight.y,
                        signaturePositionRight.width, signaturePositionRight.height);
                }

                var leftName = capitalizeFirstLetterOfEachWord(
                    "{{ $director->last_name . ' ' . $director->first_name }}");
                context.font = "bold 11px 'Lora', serif";
                var leftWidth = canvas.width / 4 + 80;
                context.fillText(leftName, leftWidth, canvas.height - 80);

                var rightName = capitalizeFirstLetterOfEachWord(
                    "{{ $teacher->last_name . ' ' . $teacher->first_name }}");
                var rightWidth = canvas.width / 2 + 160;
                context.fillText(rightName, rightWidth, canvas.height - 80);
            }

            $('.text').remove();

            $('#downloadBtn').click(function() {
                const {
                    jsPDF
                } = window.jspdf;

                var pdf = new jsPDF('landscape', 'pt', [canvas.width, canvas.height]);
                var imgData = canvas.toDataURL('image/png');

                pdf.addImage(imgData, 'PNG', 0, 0, canvas.width, canvas.height);
                pdf.save('certificate.pdf');
            });
        });
    </script>
@endsection
