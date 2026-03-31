<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sculpt API Documentation' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .endpoint { transition: all 0.2s; }
        .endpoint:hover { box-shadow: 0 0 20px rgba(16, 185, 129, 0.1); }
        .method-badge { font-weight: 600; letter-spacing: 0.05em; }
        .modal { display: none; }
        .modal.active { display: flex; }
        .fade-enter { animation: fadeIn 0.3s ease-in; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .param-input { font-family: 'Monaco', 'Menlo', monospace; }
        .response-content { max-height: 500px; overflow-y: auto; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-72 bg-slate-900 border-r border-slate-800 overflow-y-auto flex flex-col">
            <div class="p-6 border-b border-slate-800">
                <h1 class="text-3xl font-bold text-emerald-500 mb-2">Sculpt</h1>
                <p class="text-sm text-slate-400">API Documentation</p>
            </div>
            <div class="flex-1 overflow-y-auto p-4">
                <div id="sidebar" class="space-y-2">
                    <!-- Rendered by JavaScript -->
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <div class="max-w-5xl mx-auto p-8">
                <div class="mb-8">
                    <h2 class="text-4xl font-bold mb-2">{{ $spec['info']['title'] ?? 'API Documentation' }}</h2>
                    <p class="text-slate-400">{{ $spec['info']['description'] ?? '' }}</p>
                    <div class="mt-4 inline-block px-3 py-1 bg-slate-800 text-slate-300 rounded text-sm">
                        Version {{ $spec['info']['version'] ?? '1.0.0' }}
                    </div>
                </div>

                <div id="endpoints" class="space-y-6">
                    <!-- Endpoints rendered by JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Try It Out Modal -->
    <div id="tryModal" class="modal fixed inset-0 bg-black/50 z-50 items-center justify-center p-4">
        <div class="bg-slate-900 rounded-lg w-full max-w-3xl max-h-screen overflow-y-auto border border-slate-800">
            <div class="sticky top-0 bg-slate-900 border-b border-slate-800 p-6 flex justify-between items-center">
                <div>
                    <span id="modalMethod" class="px-3 py-1 text-xs font-bold rounded-lg inline-block mr-3"></span>
                    <span id="modalPath" class="font-mono text-slate-300"></span>
                </div>
                <button onclick="closeTryModal()" class="text-slate-400 hover:text-white text-2xl">&times;</button>
            </div>

            <div class="p-6">
                <!-- Request Section -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4 text-emerald-400">Request</h3>

                    <!-- Headers -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2">Headers</label>
                        <div id="headersContainer" class="space-y-2 mb-2">
                            <div class="flex gap-2">
                                <input type="text" placeholder="Header name" class="flex-1 param-input px-3 py-2 bg-slate-800 border border-slate-700 rounded text-sm" />
                                <input type="text" placeholder="Value" class="flex-1 param-input px-3 py-2 bg-slate-800 border border-slate-700 rounded text-sm" />
                                <button onclick="removeParam(this)" class="px-3 py-2 bg-red-600/20 text-red-400 rounded text-sm hover:bg-red-600/30">Remove</button>
                            </div>
                        </div>
                        <button onclick="addHeaderInput()" class="text-sm text-emerald-400 hover:text-emerald-300">+ Add Header</button>
                    </div>

                    <!-- Query Parameters -->
                    <div id="queryParamsSection" class="mb-6" style="display: none;">
                        <label class="block text-sm font-medium mb-2">Query Parameters</label>
                        <div id="queryParamsContainer" class="space-y-2 mb-2">
                        </div>
                        <button onclick="addQueryParam()" class="text-sm text-emerald-400 hover:text-emerald-300">+ Add Parameter</button>
                    </div>

                    <!-- Request Body -->
                    <div id="requestBodySection" class="mb-6" style="display: none;">
                        <label class="block text-sm font-medium mb-2">Request Body (JSON)</label>
                        <textarea id="requestBody" class="w-full h-32 param-input px-3 py-2 bg-slate-800 border border-slate-700 rounded text-sm font-mono" placeholder='{"key": "value"}'></textarea>
                    </div>

                    <button onclick="sendRequest()" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-medium py-3 rounded-lg transition-colors">
                        Send Request
                    </button>
                </div>

                <!-- Response Section -->
                <div id="responseSection" style="display: none;">
                    <h3 class="text-lg font-semibold mb-4 text-emerald-400">Response</h3>

                    <div class="mb-4">
                        <div class="flex gap-4 mb-4">
                            <div>
                                <p class="text-sm text-slate-400">Status Code</p>
                                <p id="statusCode" class="text-2xl font-bold"></p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-400">Response Time</p>
                                <p id="responseTime" class="text-2xl font-bold"></p>
                            </div>
                        </div>
                    </div>

                    <label class="block text-sm font-medium mb-2">Response Body</label>
                    <div class="response-content bg-black rounded border border-slate-700">
                        <pre class="p-4"><code id="responseBody" class="language-json"></code></pre>
                    </div>
                </div>

                <div id="loadingIndicator" style="display: none;" class="flex items-center justify-center py-8">
                    <div class="animate-spin">
                        <svg class="w-8 h-8 text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    const spec = @json($spec);
    const apiUrl = spec.servers && spec.servers[0] ? spec.servers[0].url : @json(config('app.url', 'http://localhost'));
    let currentRequest = {};

    function getMethodColor(method) {
        const colors = {
            'get': 'bg-emerald-500',
            'post': 'bg-blue-500',
            'put': 'bg-yellow-500',
            'patch': 'bg-orange-500',
            'delete': 'bg-red-500',
        };
        return colors[method] || 'bg-slate-500';
    }

    function renderSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.innerHTML = '';

        Object.keys(spec.paths || {}).forEach(path => {
            const methods = spec.paths[path];
            Object.keys(methods).forEach(method => {
                const endpoint = methods[method];
                const el = document.createElement('button');
                el.className = `w-full text-left px-4 py-2 rounded transition hover:bg-slate-800 border-l-2 border-transparent hover:border-emerald-500 group`;
                el.innerHTML = `
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold ${getMethodColor(method)} px-2 py-1 rounded text-white">${method.toUpperCase()}</span>
                        <span class="text-xs text-slate-300 truncate group-hover:text-white">${path}</span>
                    </div>
                `;
                el.onclick = () => scrollToEndpoint(path, method);
                sidebar.appendChild(el);
            });
        });
    }

    function renderEndpoints() {
        const container = document.getElementById('endpoints');
        container.innerHTML = '';

        Object.keys(spec.paths || {}).forEach(path => {
            const methods = spec.paths[path];

            Object.keys(methods).forEach(method => {
                const endpoint = methods[method];
                const card = document.createElement('div');
                card.className = 'bg-slate-900 rounded-lg p-6 endpoint border border-slate-800';
                card.id = `endpoint-${method}-${path.replace(/\//g, '-')}`;

                let requestBodyExample = '';
                if (endpoint.requestBody?.content?.['application/json']?.schema) {
                    requestBodyExample = JSON.stringify(endpoint.requestBody.content['application/json'].schema, null, 2);
                }

                let paramsExample = '';
                if (endpoint.parameters) {
                    paramsExample = endpoint.parameters.map(p => `${p.name} (${p.schema?.type || 'string'})`).join(', ');
                }

                card.innerHTML = `
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="px-3 py-1 text-xs font-bold rounded-lg text-white ${getMethodColor(method)}">
                                    ${method.toUpperCase()}
                                </span>
                                <code class="text-lg font-mono text-slate-300">${path}</code>
                            </div>
                            <h3 class="text-lg font-semibold text-white">${endpoint.summary || 'Endpoint'}</h3>
                        </div>
                    </div>

                    ${endpoint.description ? `<p class="text-slate-400 mb-4 text-sm">${endpoint.description}</p>` : ''}

                    ${endpoint.parameters ? `
                        <div class="mb-4">
                            <h4 class="text-xs uppercase tracking-wider text-slate-500 mb-2">Parameters</h4>
                            <div class="space-y-1">
                                ${endpoint.parameters.map(p => `
                                    <div class="text-sm text-slate-400">
                                        <span class="font-mono text-slate-300">${p.name}</span>
                                        <span class="text-xs bg-slate-800 px-2 py-1 rounded ml-2">${p.in || 'query'}</span>
                                        ${p.required ? '<span class="text-red-400">*</span>' : ''}
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    ` : ''}

                    ${requestBodyExample ? `
                        <div class="mb-4">
                            <h4 class="text-xs uppercase tracking-wider text-slate-500 mb-2">Request Body Example</h4>
                            <pre class="bg-black p-3 rounded text-xs overflow-auto"><code class="language-json">${escapeHtml(requestBodyExample)}</code></pre>
                        </div>
                    ` : ''}

                    <button onclick="openTryModal('${path}', '${method}', ${JSON.stringify(endpoint).split("'").join("\\'")});"
                            class="bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded font-medium transition-colors text-sm">
                        Try it out
                    </button>
                `;

                container.appendChild(card);
            });
        });

        hljs.highlightAll();
    }

    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    function openTryModal(path, method, endpoint) {
        currentRequest = { path, method, endpoint };
        document.getElementById('tryModal').classList.add('active');
        document.getElementById('modalMethod').textContent = method.toUpperCase();
        document.getElementById('modalPath').textContent = path;
        document.getElementById('responseSection').style.display = 'none';
        document.getElementById('loadingIndicator').style.display = 'none';

        // Reset form
        document.getElementById('headersContainer').innerHTML = `
            <div class="flex gap-2">
                <input type="text" placeholder="Header name" class="flex-1 param-input px-3 py-2 bg-slate-800 border border-slate-700 rounded text-sm" />
                <input type="text" placeholder="Value" class="flex-1 param-input px-3 py-2 bg-slate-800 border border-slate-700 rounded text-sm" />
                <button onclick="removeParam(this)" class="px-3 py-2 bg-red-600/20 text-red-400 rounded text-sm hover:bg-red-600/30">Remove</button>
            </div>
        `;
        document.getElementById('queryParamsContainer').innerHTML = '';
        document.getElementById('requestBody').value = '';

        // Show/hide sections based on method
        if (method.toLowerCase() === 'get' || method.toLowerCase() === 'delete') {
            document.getElementById('queryParamsSection').style.display = 'block';
            document.getElementById('requestBodySection').style.display = 'none';
        } else {
            document.getElementById('queryParamsSection').style.display = 'block';
            document.getElementById('requestBodySection').style.display = 'block';

            // Pre-fill request body if available
            if (endpoint.requestBody?.content?.['application/json']?.schema) {
                document.getElementById('requestBody').value = JSON.stringify(endpoint.requestBody.content['application/json'].schema, null, 2);
            }
        }

        // Add parameter inputs if endpoint has parameters
        if (endpoint.parameters) {
            const queryParams = endpoint.parameters.filter(p => p.in === 'query');
            if (queryParams.length > 0) {
                document.getElementById('queryParamsSection').style.display = 'block';
                const container = document.getElementById('queryParamsContainer');
                container.innerHTML = '';
                queryParams.forEach(param => {
                    addQueryParam(param.name, '');
                });
            }
        }

        document.getElementById('tryModal').style.display = 'flex';
    }

    function closeTryModal() {
        document.getElementById('tryModal').classList.remove('active');
        setTimeout(() => {
            document.getElementById('tryModal').style.display = 'none';
        }, 300);
    }

    function addHeaderInput() {
        const container = document.getElementById('headersContainer');
        const div = document.createElement('div');
        div.className = 'flex gap-2';
        div.innerHTML = `
            <input type="text" placeholder="Header name" class="flex-1 param-input px-3 py-2 bg-slate-800 border border-slate-700 rounded text-sm" />
            <input type="text" placeholder="Value" class="flex-1 param-input px-3 py-2 bg-slate-800 border border-slate-700 rounded text-sm" />
            <button onclick="removeParam(this)" class="px-3 py-2 bg-red-600/20 text-red-400 rounded text-sm hover:bg-red-600/30">Remove</button>
        `;
        container.appendChild(div);
    }

    function addQueryParam(name = '', value = '') {
        const container = document.getElementById('queryParamsContainer');
        const div = document.createElement('div');
        div.className = 'flex gap-2';
        div.innerHTML = `
            <input type="text" placeholder="Parameter name" value="${name}" class="flex-1 param-input px-3 py-2 bg-slate-800 border border-slate-700 rounded text-sm" />
            <input type="text" placeholder="Value" value="${value}" class="flex-1 param-input px-3 py-2 bg-slate-800 border border-slate-700 rounded text-sm" />
            <button onclick="removeParam(this)" class="px-3 py-2 bg-red-600/20 text-red-400 rounded text-sm hover:bg-red-600/30">Remove</button>
        `;
        container.appendChild(div);
    }

    function removeParam(btn) {
        btn.parentElement.remove();
    }

    async function sendRequest() {
        const method = currentRequest.method.toUpperCase();
        const path = currentRequest.path;
        let url = apiUrl.replace(/\/$/, '') + path;

        // Gather query parameters
        const queryParams = new URLSearchParams();
        document.querySelectorAll('#queryParamsContainer input[placeholder*="Parameter"]').forEach((input, idx) => {
            if (idx % 2 === 0) {
                const name = input.value;
                const value = input.nextElementSibling?.value || '';
                if (name) queryParams.append(name, value);
            }
        });

        if (queryParams.toString()) {
            url += '?' + queryParams.toString();
        }

        // Gather headers
        const headers = {};
        document.querySelectorAll('#headersContainer input[placeholder*="Header"]').forEach((input, idx) => {
            if (idx % 3 === 0) {
                const name = input.value;
                const value = input.nextElementSibling?.value || '';
                if (name) headers[name] = value;
            }
        });
        headers['Content-Type'] = 'application/json';
        headers['Accept'] = 'application/json';

        // Gather request body
        let body = null;
        if (method !== 'GET' && method !== 'DELETE') {
            const bodyText = document.getElementById('requestBody').value;
            if (bodyText.trim()) {
                try {
                    body = JSON.parse(bodyText);
                } catch (e) {
                    alert('Invalid JSON in request body');
                    return;
                }
            }
        }

        document.getElementById('loadingIndicator').style.display = 'flex';
        document.getElementById('responseSection').style.display = 'none';

        try {
            const startTime = performance.now();
            const response = await fetch(url, {
                method,
                headers,
                body: body ? JSON.stringify(body) : null,
            });
            const endTime = performance.now();

            let responseData;
            try {
                responseData = await response.json();
            } catch (e) {
                responseData = await response.text();
            }

            document.getElementById('loadingIndicator').style.display = 'none';
            document.getElementById('responseSection').style.display = 'block';
            document.getElementById('statusCode').textContent = response.status;
            document.getElementById('responseTime').textContent = (endTime - startTime).toFixed(2) + 'ms';
            document.getElementById('responseBody').textContent = typeof responseData === 'string' ? responseData : JSON.stringify(responseData, null, 2);
            document.getElementById('responseBody').classList.remove('language-json');
            hljs.highlightElement(document.getElementById('responseBody'));
        } catch (error) {
            document.getElementById('loadingIndicator').style.display = 'none';
            document.getElementById('responseSection').style.display = 'block';
            document.getElementById('statusCode').textContent = 'Error';
            document.getElementById('responseBody').textContent = error.message;
            document.getElementById('responseBody').classList.remove('language-json');
        }
    }

    function scrollToEndpoint(path, method) {
        const id = `endpoint-${method}-${path.replace(/\//g, '-')}`;
        const element = document.getElementById(id);
        if (element) {
            element.scrollIntoView({ behavior: 'smooth', block: 'start' });
            element.classList.add('ring-2', 'ring-emerald-500');
            setTimeout(() => {
                element.classList.remove('ring-2', 'ring-emerald-500');
            }, 2000);
        }
    }

    // Close modal when clicking outside
    document.getElementById('tryModal').addEventListener('click', (e) => {
        if (e.target.id === 'tryModal') {
            closeTryModal();
        }
    });

    // Initialize
    renderSidebar();
    renderEndpoints();
    hljs.highlightAll();
</script>
</body>
</html>