<!DOCTYPE html>
<html lang="gu">
<head>
    <meta charset="UTF-8">
    <title>Gujarati PDF Example</title>
    <style>
        body {
            font-family: 'Noto Sans Gujarati', sans-serif;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Gujarati&display=swap" rel="stylesheet">
</head>
<body>
    <div id="content">
        <h1>ગુજરાતી ડોક્યુમેન્ટ</h1>
        <p>આ પીડીએફમાં ગુજરાતી લખાણ છે.વિદ્યાર્થીનું નામ


            દિપાલી
            
            ધ્રુવિત
            
            પ્રિયંકા</p>
    </div>
    <button id="download">Download as PDF</button>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script>
        document.getElementById("download").addEventListener("click", async function () {
            const { jsPDF } = window.jspdf;

            // Capture the HTML content
            const content = document.getElementById("content");

            // Use html2canvas to convert the content into a canvas
            const canvas = await html2canvas(content);

            // Convert canvas to image data
            const imgData = canvas.toDataURL("image/png");

            // Create a PDF and add the image data
            const doc = new jsPDF();
            doc.addImage(imgData, "PNG", 10, 10, 180, canvas.height * 180 / canvas.width); // Adjust height to keep the aspect ratio
            doc.save("Gujarati_Document.pdf");
        });
    </script>
</body>
</html>