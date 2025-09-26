const pdfUrl = "http://www.taobaoyanji.com/uploads/20250924/6952bbffa8e0f82f4351b5574acc91c6.pdf";

fetch(pdfUrl)
    .then(res => res.arrayBuffer())
    .then(buffer => pdfjsLib.getDocument({data: buffer}).promise)
    .then(pdf => pdf.getPage(1))
    .then(page => {
        const viewport = page.getViewport({ scale: 2.0 });
        const canvas = document.getElementById('pdf-canvas');
        const context = canvas.getContext('2d');

        canvas.width = viewport.width;
        canvas.height = viewport.height;

        return page.render({ canvasContext: context, viewport }).promise;
    });
