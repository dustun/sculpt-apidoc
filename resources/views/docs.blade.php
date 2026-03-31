<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; }
        pre { font-size: 0.92rem; line-height: 1.5; }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-200 min-h-screen">
<div class="max-w-7xl mx-auto p-6">
    <div class="mb-10 flex items-center justify-between">
        <h1 class="text-4xl font-bold text-emerald-400 tracking-tight">Sculpt</h1>
        <p class="text-zinc-400">API Documentation</p>
    </div>

    <div class="bg-zinc-900 rounded-3xl p-8 shadow-xl border border-zinc-800">
        <pre class="overflow-auto max-h-[88vh] p-6 rounded-2xl bg-black text-sm"><code class="language-json">{{ $spec }}</code></pre>
    </div>
</div>

<script>
    Prism.highlightAll();
</script>
</body>
</html>