@extends('auth.main')

@section('head')
    <link rel="stylesheet" href="{{ asset('assets/css/certificateFonts.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
@endsection

@section('contents')
    <h1 class="text" style="font-family: Great Vibes">Hello</h1>
    <h1 class="text" style="font-family: Lora">Hello</h1>
    <canvas id="certificateCanvas" width="1100" height="600"></canvas>
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
            var signatureHeight = canvas.height * 23 / 32;
            var signaturePositionLeft = {
                x: (canvas.width * 17 / 64),
                y: signatureHeight,
                width: 150,
                height: 75
            };
            var signaturePositionRight = {
                x: (canvas.width * 19 / 32),
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
                signatureLoadedLeft = true;
            };
            signatureImgLeft.src = signatureLeftSrc;

            signatureImgRight.onload = function() {
                signatureCanvasRight.width = signatureImgRight.width;
                signatureCanvasRight.height = signatureImgRight.height;
                var tempContext = signatureCanvasRight.getContext('2d');
                tempContext.drawImage(signatureImgRight, 0, 0);
                signatureLoadedRight = true;
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
                context.fillText(name, canvas.width / 2, (canvas.height * 9 / 16));

                var description =
                    'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.';
                context.font = "13px 'Lora', serif";
                var maxWidth = canvas.width - 500;
                var lineHeight = 16;
                wrapText(context, description, canvas.width / 2, canvas.height * 10 / 16 , maxWidth, lineHeight);

                var namedescription = 'of internship'.toUpperCase();
                context.font = "bold 34px 'Lora', serif";
                // wrapText(context, namedescription, canvas.width / 2, canvas.height * 23 / 64, maxWidth, lineHeight);
                context.fillText(namedescription, canvas.width / 2, canvas.height * 23 / 64);

                if (signatureLoadedLeft) {
                    context.drawImage(signatureCanvasLeft, signaturePositionLeft.x, signaturePositionLeft.y,
                        signaturePositionLeft.width, signaturePositionLeft.height);
                }

                if (signatureLoadedRight) {
                    context.drawImage(signatureCanvasRight, signaturePositionRight.x, signaturePositionRight.y,
                        signaturePositionRight.width, signaturePositionRight.height);
                }
                var height = canvas.height * 14 / 16;
                var leftName = capitalizeFirstLetterOfEachWord(
                    "{{ $director->last_name . ' ' . $director->first_name }}");
                context.font = "bold 11px 'Lora', serif";
                var leftWidth = canvas.width * 21 / 64;
                context.fillText(leftName, leftWidth, height);

                var rightName = capitalizeFirstLetterOfEachWord(
                    "{{ $teacher->last_name . ' ' . $teacher->first_name }}");
                var rightWidth = canvas.width * 42 / 64;
                context.fillText(rightName, rightWidth, height);
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
