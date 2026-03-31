<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sculpt API Documentation' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.9.0/highlight.min.js"></script>
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .endpoint { transition: all 0.2s; }
        .endpoint:hover { transform: translateX(4px); }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-100">
<div class="flex h-screen">
    <!-- Sidebar -->
    <div class="w-80 bg-zinc-900 border-r border-zinc-800 overflow-y-auto">
        <div class="p-6">
            <h1 class="text-3xl font-bold text-emerald-400 mb-8">Sculpt</h1>
            <div id="sidebar" class="space-y-8">
                <!-- Будет заполнено JavaScript -->
            </div>
        </div>
    </div>

    <div class="flex-1 overflow-auto p-8" id="main-content">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-4xl font-semibold mb-10" id="page-title">API Documentation</h2>
            <div id="endpoints" class="space-y-12">
            </div>
        </div>
    </div>
</div>

<script>
    const spec = @json($spec);

    function renderEndpoints() {
        const container = document.getElementById('endpoints');
        container.innerHTML = '';

        Object.keys(spec.paths).forEach(path => {
            const methods = spec.paths[path];

            Object.keys(methods).forEach(method => {
                const endpoint = methods[method];
                const card = document.createElement('div');
                card.className = 'bg-zinc-900 rounded-2xl p-8 endpoint border border-zinc-800';

                card.innerHTML = `
                        <div class="flex items-center gap-3 mb-4">
                            <span class="px-3 py-1 text-xs font-mono font-bold rounded-lg ${method === 'get' ? 'bg-emerald-500' : method === 'post' ? 'bg-blue-500' : 'bg-orange-500'}">
                                ${method.toUpperCase()}
                            </span>
                            <code class="text-lg font-mono text-zinc-300">${path}</code>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">${endpoint.summary || 'No summary'}</h3>
                        <p class="text-zinc-400 mb-6">${endpoint.description || ''}</p>

                        <!-- Request Body -->
                        ${endpoint.requestBody ? `
                        <div class="mb-8">
                            <h4 class="text-sm uppercase tracking-widest text-zinc-500 mb-3">Request Body</h4>
                            <pre class="bg-black p-5 rounded-xl text-sm overflow-auto"><code class="language-json">${JSON.stringify(endpoint.requestBody.content['application/json'].schema, null, 2)}</code></pre>
                        </div>` : ''}

                        <!-- Try it out button -->
                        <button onclick="tryItOut('${path}', '${method}')"
                                class="bg-emerald-600 hover:bg-emerald-500 px-6 py-3 rounded-xl font-medium transition-colors">
                            Try it out →
                        </button>
                    `;

                container.appendChild(card);
            });
        });
    }

    function tryItOut(path, method) {
        alert(`Try it out для ${method.toUpperCase()} ${path}\n\n(Здесь будет форма для отправки запроса в следующей версии)`);
    }

    renderEndpoints();
    Prism.highlightAll();
</script>
</body>
</html>