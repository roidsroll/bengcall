import { copyFileSync, mkdirSync, readdirSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const scriptDir = dirname(fileURLToPath(import.meta.url));
const rootDir = join(scriptDir, '..');
const srcDir = join(rootDir, 'node_modules', '@fortawesome', 'fontawesome-free', 'webfonts');
const destDir = join(rootDir, 'public', 'webfonts');

mkdirSync(destDir, { recursive: true });

for (const filename of readdirSync(srcDir)) {
    copyFileSync(join(srcDir, filename), join(destDir, filename));
}

console.log(`[fontawesome] Copied webfonts to ${destDir}`);

