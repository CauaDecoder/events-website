import { existsSync } from "node:fs";
import { spawn } from "node:child_process";
import { dirname, resolve } from "node:path";
import { fileURLToPath } from "node:url";

const rootDir = resolve(dirname(fileURLToPath(import.meta.url)), "..");
const bundledPnpm = "C:\\Users\\anyyt\\.cache\\codex-runtimes\\codex-primary-runtime\\dependencies\\bin\\fallback\\pnpm.cmd";
const bundledPython = "C:\\Users\\anyyt\\.cache\\codex-runtimes\\codex-primary-runtime\\dependencies\\python\\python.exe";
const bundledNode = "C:\\Users\\anyyt\\.cache\\codex-runtimes\\codex-primary-runtime\\dependencies\\node\\bin\\node.exe";

const pnpm = existsSync(bundledPnpm) ? bundledPnpm : "pnpm";
const python = existsSync(bundledPython) ? bundledPython : "python";
const node = existsSync(bundledNode) ? bundledNode : "node";
const apiVenvPython = resolve(rootDir, "apps", "api", ".venv", "Scripts", "python.exe");
const apiPython = existsSync(apiVenvPython) ? apiVenvPython : python;
const nextBin = resolve(rootDir, "apps", "web", "node_modules", "next", "dist", "bin", "next");

function run(command, args, options = {}) {
  const child = spawn(command, args, {
    cwd: options.cwd ?? rootDir,
    stdio: "inherit",
    shell: options.shell ?? false,
  });

  child.on("exit", (code, signal) => {
    if (signal || (typeof code === "number" && code !== 0)) {
      process.exitCode = typeof code === "number" ? code : 1;
      shutdown();
    }
  });

  return child;
}

function runAndWait(command, args, options = {}) {
  return new Promise((resolvePromise, rejectPromise) => {
    const child = spawn(command, args, {
      cwd: options.cwd ?? rootDir,
      stdio: "inherit",
      shell: options.shell ?? false,
    });

    child.on("exit", (code, signal) => {
      if (signal || (typeof code === "number" && code !== 0)) {
        rejectPromise(new Error(`${command} exited with ${signal ?? code}`));
        return;
      }

      resolvePromise();
    });

    child.on("error", rejectPromise);
  });
}

async function ensureWebDeps() {
  const nodeModules = resolve(rootDir, "apps", "web", "node_modules");
  if (!existsSync(nodeModules)) {
    await runAndWait(pnpm, ["install", "--ignore-scripts"], {
      cwd: resolve(rootDir, "apps", "web"),
      shell: true,
    });
  }
}

async function ensureApiDeps() {
  if (!existsSync(apiVenvPython)) {
    await runAndWait(python, ["-m", "venv", ".venv"], { cwd: resolve(rootDir, "apps", "api") });
  }

  try {
    await runAndWait(
      apiPython,
      ["-c", 'import fastapi, uvicorn, pydantic_settings, multipart'],
      { cwd: resolve(rootDir, "apps", "api") },
    );
  } catch {
    await runAndWait(
      apiPython,
      [
        "-m",
        "pip",
        "install",
        "fastapi>=0.115.0",
        "uvicorn[standard]>=0.30.0",
        "pydantic-settings>=2.4.0",
        "python-multipart>=0.0.9",
      ],
      { cwd: resolve(rootDir, "apps", "api") },
    );
  }
}

let webProcess;
let apiProcess;

function shutdown() {
  for (const child of [webProcess, apiProcess]) {
    if (child && !child.killed) {
      child.kill();
    }
  }
}

process.on("SIGINT", () => {
  shutdown();
  process.exit(0);
});

process.on("SIGTERM", () => {
  shutdown();
  process.exit(0);
});

try {
  await ensureWebDeps();
  await ensureApiDeps();

  apiProcess = run(apiPython, ["-m", "uvicorn", "app.main:app", "--reload", "--port", "8000"], {
    cwd: resolve(rootDir, "apps", "api"),
  });

  webProcess = run(node, [nextBin, "dev", "--hostname", "127.0.0.1"], {
    cwd: resolve(rootDir, "apps", "web"),
  });
} catch (error) {
  console.error(error instanceof Error ? error.message : error);
  process.exit(1);
}
