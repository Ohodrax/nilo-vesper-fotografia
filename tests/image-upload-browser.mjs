import assert from 'node:assert/strict';
import { createServer } from 'node:http';
import { once } from 'node:events';
import { readFile } from 'node:fs/promises';
import { chromium } from '@playwright/test';

const script = await readFile(new URL('../scripts/image-upload.js', import.meta.url));
const server = createServer((request, response) => {
  response.setHeader('Content-Type', request.url === '/image-upload.js' ? 'text/javascript' : 'text/html');
  response.end(request.url === '/image-upload.js' ? script : '<!doctype html><script src="/image-upload.js"></script>');
});
server.listen(0, '127.0.0.1');
await once(server, 'listening');
let browser;
try {
  browser = await chromium.launch({ channel: process.env.PLAYWRIGHT_CHANNEL || undefined });
  const page = await browser.newPage();
  await page.goto('http://127.0.0.1:' + server.address().port);
  const results = await page.evaluate(async () => {
    async function source(width, height, type = 'image/jpeg', noise = false) {
      const canvas = document.createElement('canvas');
      canvas.width = width; canvas.height = height;
      const context = canvas.getContext('2d');
      if (noise) {
        const pixels = context.createImageData(width, height);
        let seed = 17;
        for (let i = 0; i < pixels.data.length; i += 4) {
          seed = (seed * 1664525 + 1013904223) >>> 0;
          pixels.data[i] = seed & 255; pixels.data[i + 1] = (seed >>> 8) & 255;
          pixels.data[i + 2] = (seed >>> 16) & 255; pixels.data[i + 3] = 255;
        }
        context.putImageData(pixels, 0, 0);
      } else { context.fillStyle = '#2255aa'; context.fillRect(0, 0, width / 2, height); }
      const blob = await new Promise(resolve => canvas.toBlob(resolve, type, 0.95));
      canvas.width = canvas.height = 0;
      return new File([blob], 'source.' + (type === 'image/png' ? 'png' : 'jpg'), { type });
    }
    const original = await source(6000, 4000);
    const originalBytes = original.size;
    const jpeg = await PhotoUpload.prepare(original);
    const png = await PhotoUpload.prepare(await source(100, 80, 'image/png'));
    const bitmap = await createImageBitmap(png.file);
    const check = document.createElement('canvas'); check.width = 100; check.height = 80;
    const context = check.getContext('2d'); context.drawImage(bitmap, 0, 0);
    const alpha = context.getImageData(99, 79, 1, 1).data[3]; bitmap.close();
    const noisy = await source(1400, 1400, 'image/png', true);
    const compact = await PhotoUpload.prepare(noisy, { maxBytes: 256 * 1024 });
    const runtime = await PhotoUpload.prepare(original, { maxBytes: 1024 * 1024, maxPixels: 2000000 });
    const small = await PhotoUpload.prepare(await source(120, 80));
    // JPEG APP1 with TIFF orientation 6 (90 degrees clockwise).
    const plain = new Uint8Array(await (await source(120, 80)).arrayBuffer());
    const exif = new Uint8Array([255,225,0,34,69,120,105,102,0,0,73,73,42,0,8,0,0,0,1,0,18,1,3,0,1,0,0,0,6,0,0,0,0,0,0,0]);
    const rotated = new File([plain.slice(0,2), exif, plain.slice(2)], 'rotated.jpg', { type: 'image/jpeg' });
    const oriented = await PhotoUpload.prepare(rotated);
    const failures = [];
    for (const file of [
      new File(['<svg onload="alert(1)"/>'], 'forged.png', { type: 'image/png' }),
      new File(['not an image'], 'photo.heic', { type: 'image/heic' }),
      new File([new Uint8Array(41 * 1024 * 1024)], 'huge.jpg', { type: 'image/jpeg' })
    ]) { try { await PhotoUpload.prepare(file); failures.push(false); } catch { failures.push(true); } }
    return {
      jpeg: { width: jpeg.width, height: jpeg.height, size: jpeg.file.size, type: jpeg.file.type },
      originalUnchanged: original.size === originalBytes, alpha,
      compact: { size: compact.file.size, originalSize: noisy.size },
      runtime: { pixels: runtime.width * runtime.height, size: runtime.file.size },
      small: [small.width, small.height], oriented: [oriented.width, oriented.height], failures
    };
  });
  assert.deepEqual([results.jpeg.width, results.jpeg.height], [3200, 2133]);
  assert.ok(results.jpeg.size <= 8 * 1024 * 1024);
  assert.equal(results.jpeg.type, 'image/jpeg');
  assert.equal(results.originalUnchanged, true);
  assert.equal(results.alpha, 0, 'PNG transparency');
  assert.ok(results.compact.originalSize > 256 * 1024 && results.compact.size <= 256 * 1024, 'Reduce actual encoded bytes');
  assert.ok(results.runtime.pixels <= 2000000 && results.runtime.size <= 1024 * 1024, 'Honor lower PHP limits');
  assert.deepEqual(results.small, [120, 80], 'Never upscale small originals');
  assert.deepEqual(results.oriented, [80, 120], 'Keep EXIF orientation');
  assert.deepEqual(results.failures, [true, true, true]);
  console.log('Browser image checks passed: 24 MP JPEG, PNG alpha, byte/pixel limits, small photos, EXIF rotation and invalid inputs.');
} finally {
  if (browser) await browser.close();
  await new Promise(resolve => server.close(resolve));
}
