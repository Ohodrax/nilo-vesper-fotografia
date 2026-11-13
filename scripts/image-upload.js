// Optimize a copy for the website; the original file on the device is untouched.
(function () {
    const sourceMaxBytes = 40 * 1024 * 1024;
    const sourceMaxPixels = 60000000;

    function dimensions(buffer) {
        const bytes = new Uint8Array(buffer);
        const view = new DataView(buffer);
        if (bytes.length >= 24 && [137, 80, 78, 71, 13, 10, 26, 10].every((value, i) => bytes[i] === value)) {
            return { width: view.getUint32(16), height: view.getUint32(20), type: 'image/png' };
        }
        if (bytes[0] === 255 && bytes[1] === 216) {
            let offset = 2;
            while (offset + 3 < bytes.length) {
                if (bytes[offset++] !== 255) break;
                while (bytes[offset] === 255) offset++;
                const marker = bytes[offset++];
                if (marker === 218 || marker === 217) break;
                if (offset + 2 > bytes.length) break;
                const length = view.getUint16(offset);
                if (length < 2 || offset + length > bytes.length) break;
                if (marker >= 192 && marker <= 207 && ![196, 200, 204].includes(marker) && length >= 7) {
                    return { width: view.getUint16(offset + 5), height: view.getUint16(offset + 3), type: 'image/jpeg' };
                }
                offset += length;
            }
        }
        throw new Error('Não foi possível ler a imagem. Use uma foto JPEG ou PNG válida; HEIC, RAW e SVG precisam ser convertidos antes.');
    }

    async function prepare(file, options = {}) {
        const limits = { maxBytes: 8 * 1024 * 1024, maxPixels: 8000000, maxEdge: 3200, ...options };
        if (file.size > sourceMaxBytes) throw new Error('O original ultrapassa 40 MB. Exporte uma cópia menor em JPEG ou PNG.');
        if (limits.maxBytes < 65536 || limits.maxPixels < 65536) throw new Error('O servidor está sem capacidade para receber imagens. Revise os limites de PHP na hospedagem.');
        const info = dimensions(await file.arrayBuffer());
        if (!info.width || !info.height || info.width * info.height > sourceMaxPixels) throw new Error('O original ultrapassa 60 megapixels ou tem dimensões inválidas. Exporte uma cópia menor para o site.');
        let bitmap;
        try { bitmap = await createImageBitmap(file); }
        catch { throw new Error('Não foi possível abrir esta imagem. Exporte novamente como JPEG ou PNG e tente de novo.'); }
        const canvas = document.createElement('canvas');
        try {
            const scale = Math.min(1, limits.maxEdge / Math.max(bitmap.width, bitmap.height), Math.sqrt(limits.maxPixels / (bitmap.width * bitmap.height)));
            let width = Math.max(1, Math.floor(bitmap.width * scale));
            let height = Math.max(1, Math.floor(bitmap.height * scale));
            for (let attempt = 0; attempt < 16; attempt++) {
                canvas.width = width; canvas.height = height;
                const context = canvas.getContext('2d');
                if (!context) throw new Error('Seu navegador não conseguiu preparar a imagem. Tente uma foto menor.');
                context.drawImage(bitmap, 0, 0, width, height);
                const blob = await new Promise(resolve => canvas.toBlob(resolve, info.type, 0.9));
                if (!blob) throw new Error('Não foi possível gerar a cópia da imagem para envio.');
                if (blob.size <= limits.maxBytes) {
                    return { file: new File([blob], 'photo.' + (info.type === 'image/png' ? 'png' : 'jpg'), { type: info.type }), width, height };
                }
                width = Math.max(1, Math.floor(width * 0.8));
                height = Math.max(1, Math.floor(height * 0.8));
            }
            throw new Error('Não foi possível reduzir a imagem ao limite da hospedagem. Tente uma cópia menor.');
        } finally {
            bitmap.close();
            canvas.width = canvas.height = 0;
        }
    }
    globalThis.PhotoUpload = { prepare, dimensions };
})();
