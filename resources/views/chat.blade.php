<x-layouts::app :title="__('AI Chat')">
    <div class="flex h-full w-full flex-1 flex-col overflow-hidden bg-white dark:bg-zinc-900">
        <!-- Chat Header - Minimalist -->
        <div class="flex items-center justify-between border-b border-neutral-100 px-6 py-4 dark:border-neutral-800">
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <div
                        class="flex size-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                        <flux:icon name="sparkles" variant="solid" class="size-5" />
                    </div>
                    <flux:heading level="2" size="lg" class="font-semibold tracking-tight">AI Chat
                    </flux:heading>
                </div>
            </div>
            <div class="flex items-center gap-1">
                <flux:button id="clearBtn" icon="trash" variant="ghost" size="sm" inset="top bottom"
                    title="Clear chat" />
                <flux:button id="scrollDownBtn" icon="arrow-down" variant="ghost" size="sm" inset="top bottom"
                    title="Scroll to bottom" />
            </div>
        </div>

        <!-- Chat Messages area -->
        <div id="chatMain" class="flex-1 overflow-y-auto px-4 py-8 md:px-6">
            <div id="messagesContainer" class="mx-auto flex max-w-3xl flex-col gap-8">
                <!-- AI Greeting -->
                <div class="flex items-start gap-4 assistant-msg group">
                    <div
                        class="flex size-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 text-white shadow-sm">
                        <flux:icon name="sparkles" variant="solid" class="size-5" />
                    </div>
                    <div class="flex flex-1 flex-col gap-2 pt-1">
                        <div
                            class="prose prose-neutral dark:prose-invert max-w-none text-base leading-relaxed text-neutral-800 dark:text-neutral-200">
                            👋 Hello! I'm your AI assistant. Ask me anything.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Input area -->
        <div class="relative bg-white pb-6 dark:bg-zinc-900">
            <div class="mx-auto max-w-3xl px-4">
                <div
                    class="relative flex items-end overflow-hidden rounded-[28px] border border-neutral-200 bg-neutral-50 px-4 py-2 transition-all focus-within:border-neutral-300 focus-within:ring-4 focus-within:ring-neutral-500/5 dark:border-neutral-700 dark:bg-zinc-800 dark:focus-within:border-neutral-600">
                    <textarea id="message" rows="1"
                        class="block w-full resize-none border-none bg-transparent py-3 pr-12 pl-2 text-base text-neutral-800 placeholder:text-neutral-500 focus:ring-0 dark:text-neutral-200 dark:placeholder:text-neutral-400"
                        placeholder="Enter a prompt here"></textarea>

                    <div class="absolute right-2 bottom-2 flex items-center gap-1">
                        <button id="sendBtn"
                            class="flex size-10 items-center justify-center rounded-full text-neutral-400 transition-colors hover:bg-neutral-200 hover:text-neutral-700 disabled:opacity-30 dark:text-neutral-500 dark:hover:bg-neutral-700 dark:hover:text-neutral-300">
                            <flux:icon name="paper-airplane" variant="solid" class="size-5" />
                        </button>
                    </div>
                </div>
                <div class="mt-3 text-center">
                    <flux:text size="xs" class="text-neutral-400 dark:text-neutral-500">
                        AI Chat may display inaccurate info, including about people, so double-check its responses.
                    </flux:text>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/dompurify@3.1.6/dist/purify.min.js"></script>

        <script>
            // DOM elements
            const messagesContainer = document.getElementById('messagesContainer');
            const chatMain = document.getElementById('chatMain');
            const messageInput = document.getElementById('message');
            const sendBtn = document.getElementById('sendBtn');
            const clearBtn = document.getElementById('clearBtn');
            const scrollDownBtn = document.getElementById('scrollDownBtn');

            let activeEventSource = null;
            let isStreamingActive = false;
            let currentStreamBubble = null;
            let typingRowElement = null;

            // Helper: get current time string
            function getCurrentTime() {
                return new Date().toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            // Scroll to bottom of chat
            function scrollToBottom(behavior = 'smooth') {
                chatMain.scrollTo({
                    top: chatMain.scrollHeight,
                    behavior: behavior
                });
            }

            // Remove typing indicator if exists
            function removeTypingIndicator() {
                if (typingRowElement && typingRowElement.parentNode) {
                    typingRowElement.remove();
                    typingRowElement = null;
                }
            }

            // Show "Assistant is typing..." animation
            function showTypingIndicator() {
                removeTypingIndicator();
                const typingDiv = document.createElement('div');
                typingDiv.className = 'flex items-start gap-4 assistant-msg';
                typingDiv.id = 'typingIndicatorMsg';
                typingDiv.innerHTML = `
                <div class="flex size-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 text-white shadow-sm">
                    <svg class="size-5 animate-pulse" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22ZM12 17L10.5 13.5L7 12L10.5 10.5L12 7L13.5 10.5L17 12L13.5 13.5L12 17Z"></path></svg>
                </div>
                <div class="flex flex-1 flex-col gap-2 pt-1">
                    <div class="flex items-center gap-1.5 h-6">
                        <span class="size-1.5 animate-bounce rounded-full bg-indigo-400"></span>
                        <span class="size-1.5 animate-bounce rounded-full bg-indigo-400 [animation-delay:0.2s]"></span>
                        <span class="size-1.5 animate-bounce rounded-full bg-indigo-400 [animation-delay:0.4s]"></span>
                    </div>
                </div>
            `;
                messagesContainer.appendChild(typingDiv);
                typingRowElement = typingDiv;
                scrollToBottom();
            }

            // Add a new user or assistant message (without streaming)
            function addStaticMessage(role, text) {
                const messageDiv = document.createElement('div');
                messageDiv.className = `flex items-start gap-4 ${role === 'user' ? 'user-msg' : 'assistant-msg'}`;

                const avatar = role === 'assistant' ?
                    `<div class="flex size-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 text-white shadow-sm"><svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22ZM12 17L10.5 13.5L7 12L10.5 10.5L12 7L13.5 10.5L17 12L13.5 13.5L12 17Z"></path></svg></div>` :
                    `<div class="flex size-8 shrink-0 items-center justify-center rounded-full bg-neutral-200 text-neutral-600 dark:bg-neutral-700 dark:text-neutral-300 font-bold text-xs">U</div>`;

                const bubbleContent = role === 'assistant' ? DOMPurify.sanitize(marked.parse(text)) : escapeHtml(text);

                const contentClasses = role === 'assistant' ?
                    'prose prose-neutral dark:prose-invert max-w-none text-base leading-relaxed text-neutral-800 dark:text-neutral-200' :
                    'rounded-2xl bg-neutral-100 px-5 py-3 text-base text-neutral-800 dark:bg-zinc-800 dark:text-neutral-200 max-w-[85%] ml-auto';

                if (role === 'user') {
                    messageDiv.innerHTML = `
                    <div class="flex flex-1 flex-col items-end gap-1">
                        <div class="${contentClasses}">${bubbleContent}</div>
                        <div class="px-2 text-[10px] text-neutral-400 dark:text-neutral-500">${getCurrentTime()}</div>
                    </div>
                    ${avatar}
                `;
                } else {
                    messageDiv.innerHTML = `
                    ${avatar}
                    <div class="flex flex-1 flex-col gap-1 pt-1">
                        <div class="${contentClasses}">${bubbleContent}</div>
                        <div class="px-1 text-[10px] text-neutral-400 dark:text-neutral-500">${getCurrentTime()}</div>
                    </div>
                `;
                }

                messagesContainer.appendChild(messageDiv);
                scrollToBottom();
                return messageDiv.querySelector('.prose') || messageDiv.querySelector('.rounded-2xl');
            }

            // Helper to escape HTML for user messages
            function escapeHtml(str) {
                if (!str) return '';
                return str.replace(/[&<>]/g, function(m) {
                    if (m === '&') return '&amp;';
                    if (m === '<') return '&lt;';
                    if (m === '>') return '&gt;';
                    return m;
                }).replace(/[\uD800-\uDBFF][\uDC00-\uDFFF]/g, function(c) {
                    return c;
                });
            }

            // Create an empty assistant bubble for streaming
            function createStreamingAssistantBubble() {
                removeTypingIndicator();
                const messageDiv = document.createElement('div');
                messageDiv.className = `flex items-start gap-4 assistant-msg`;
                messageDiv.innerHTML = `
                <div class="flex size-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 text-white shadow-sm">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22ZM12 17L10.5 13.5L7 12L10.5 10.5L12 7L13.5 10.5L17 12L13.5 13.5L12 17Z"></path></svg>
                </div>
                <div class="flex flex-1 flex-col gap-1 pt-1">
                    <div class="bubble prose prose-neutral dark:prose-invert max-w-none text-base leading-relaxed text-neutral-800 dark:text-neutral-200" data-raw="">
                        <span class="inline-block h-5 w-1.5 animate-pulse bg-indigo-400 align-middle rounded-full"></span>
                    </div>
                    <div class="px-1 text-[10px] text-neutral-400 dark:text-neutral-500">${getCurrentTime()}</div>
                </div>
            `;
                messagesContainer.appendChild(messageDiv);
                const bubble = messageDiv.querySelector('.bubble');
                bubble.dataset.raw = '';
                scrollToBottom();
                return bubble;
            }

            // Append text chunk to streaming bubble with Markdown rendering
            function appendStreamChunk(bubble, chunk) {
                let currentRaw = bubble.dataset.raw || '';
                currentRaw += chunk;
                bubble.dataset.raw = currentRaw;
                let renderedHtml = DOMPurify.sanitize(marked.parse(currentRaw));
                if (isStreamingActive) {
                    // add cursor at the end while still streaming
                    renderedHtml +=
                        '<span class="inline-block h-5 w-1.5 animate-pulse bg-indigo-400 align-middle rounded-full"></span>';
                }
                bubble.innerHTML = renderedHtml;
                scrollToBottom();
            }

            // Finish streaming: clean up, remove cursor, enable inputs
            function finishStreaming() {
                if (activeEventSource) {
                    try {
                        activeEventSource.close();
                    } catch (e) {}
                    activeEventSource = null;
                }
                if (isStreamingActive) {
                    isStreamingActive = false;
                    if (currentStreamBubble) {
                        let finalHtml = currentStreamBubble.innerHTML.replace(
                            '<span class="inline-block h-5 w-1.5 animate-pulse bg-indigo-400 align-middle rounded-full"></span>',
                            '');
                        currentStreamBubble.innerHTML = finalHtml;
                        // if bubble is empty, set fallback
                        if (!currentStreamBubble.dataset.raw || currentStreamBubble.dataset.raw.trim() === '') {
                            currentStreamBubble.innerHTML =
                                '<span class="text-neutral-400 italic">No response received.</span>';
                        }
                        currentStreamBubble = null;
                    }
                }
                removeTypingIndicator();
                // enable UI
                sendBtn.disabled = false;
                messageInput.disabled = false;
                messageInput.focus();
            }

            // Parse Vercel / data protocol stream chunks (supports 0:"text" format)
            function parseStreamChunk(data) {
                if (!data || data === '[DONE]') return null;
                // handle 0:"content"
                const quotedMatch = data.match(/^0:"(.*)"$/);
                if (quotedMatch) {
                    try {
                        return JSON.parse(`"${quotedMatch[1]}"`);
                    } catch (e) {
                        return quotedMatch[1];
                    }
                }
                // handle 0:plain text (no quotes)
                const plainMatch = data.match(/^0:(.*)$/);
                if (plainMatch) {
                    try {
                        const parsed = JSON.parse(plainMatch[1]);
                        if (typeof parsed === 'string') return parsed;
                        return plainMatch[1];
                    } catch (e) {
                        return plainMatch[1];
                    }
                }
                // try JSON
                try {
                    const obj = JSON.parse(data);
                    if (typeof obj === 'string') return obj;
                    if (obj.text) return obj.text;
                    if (obj.content) return obj.content;
                    if (obj.delta) return obj.delta;
                    return null;
                } catch (e) {
                    return data.length > 1 ? data : null;
                }
            }

            // Start SSE stream
            function startStreaming(userMessage) {
                // abort any existing stream
                if (activeEventSource) {
                    activeEventSource.close();
                    activeEventSource = null;
                }
                finishStreaming(); // ensure clean state

                isStreamingActive = true;
                showTypingIndicator();

                // Create the assistant bubble that will be filled with streaming content
                currentStreamBubble = createStreamingAssistantBubble();

                const url = new URL('{{ route('chat.stream') }}', window.location.origin);
                url.searchParams.set('query', userMessage);

                activeEventSource = new EventSource(url.toString());

                activeEventSource.onmessage = (event) => {
                    // remove typing indicator on first data chunk
                    if (typingRowElement) removeTypingIndicator();
                    const chunkText = parseStreamChunk(event.data);
                    if (chunkText && typeof chunkText === 'string' && chunkText.length > 0) {
                        if (currentStreamBubble) {
                            appendStreamChunk(currentStreamBubble, chunkText);
                        }
                    } else if (event.data === '[DONE]') {
                        finishStreaming();
                    }
                };

                activeEventSource.onerror = (err) => {
                    if (currentStreamBubble && (!currentStreamBubble.dataset.raw || currentStreamBubble.dataset.raw
                            .length === 0)) {
                        currentStreamBubble.innerHTML =
                            '<span class="text-red-500">⚠️ Connection error. Please try again.</span>';
                    }
                    finishStreaming();
                };

                // Handle custom finish/done events
                activeEventSource.addEventListener('finish', () => finishStreaming());
                activeEventSource.addEventListener('done', () => finishStreaming());

                // safety timeout (60 seconds)
                setTimeout(() => {
                    if (isStreamingActive && activeEventSource) {
                        if (currentStreamBubble && (!currentStreamBubble.dataset.raw || currentStreamBubble.dataset.raw
                                .length < 3)) {
                            appendStreamChunk(currentStreamBubble, " [timeout]");
                        }
                        finishStreaming();
                    }
                }, 60000);
            }

            // Send a new user message
            function sendMessage() {
                const text = messageInput.value.trim();
                if (!text) return;
                if (isStreamingActive) {
                    // if streaming, cancel current stream gracefully
                    if (activeEventSource) {
                        activeEventSource.close();
                        activeEventSource = null;
                    }
                    finishStreaming();
                }

                // Add user message to UI
                addStaticMessage('user', text);

                // Clear input & resize
                messageInput.value = '';
                adjustTextareaHeight();

                // Disable UI during streaming
                sendBtn.disabled = true;
                messageInput.disabled = true;

                // start AI stream
                startStreaming(text);
            }

            // Clear entire conversation (keep only a clean welcome message)
            function clearConversation() {
                if (isStreamingActive) {
                    if (activeEventSource) {
                        activeEventSource.close();
                        activeEventSource = null;
                    }
                    finishStreaming();
                }
                // Remove all messages except a fresh one
                while (messagesContainer.firstChild) {
                    messagesContainer.removeChild(messagesContainer.firstChild);
                }
                // add fresh assistant greeting
                const freshDiv = document.createElement('div');
                freshDiv.className = 'flex items-start gap-4 assistant-msg';
                freshDiv.innerHTML = `
                <div class="flex size-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 text-white shadow-sm">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22ZM12 17L10.5 13.5L7 12L10.5 10.5L12 7L13.5 10.5L17 12L13.5 13.5L12 17Z"></path></svg>
                </div>
                <div class="flex flex-1 flex-col gap-1 pt-1">
                    <div class="prose prose-neutral dark:prose-invert max-w-none text-base leading-relaxed text-neutral-800 dark:text-neutral-200">✨ Conversation cleared. How can I help you today?</div>
                    <div class="px-1 text-[10px] text-neutral-400 dark:text-neutral-500">${getCurrentTime()}</div>
                </div>
            `;
                messagesContainer.appendChild(freshDiv);
                scrollToBottom();
                sendBtn.disabled = false;
                messageInput.disabled = false;
                messageInput.focus();
            }

            // Auto-resize textarea
            function adjustTextareaHeight() {
                messageInput.style.height = 'auto';
                messageInput.style.height = Math.min(messageInput.scrollHeight, 200) + 'px';
            }

            // Event listeners
            messageInput.addEventListener('input', adjustTextareaHeight);
            messageInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    if (!sendBtn.disabled && messageInput.value.trim()) {
                        sendMessage();
                    }
                }
            });

            sendBtn.addEventListener('click', () => {
                if (!sendBtn.disabled && messageInput.value.trim()) {
                    sendMessage();
                }
            });

            clearBtn.addEventListener('click', clearConversation);
            scrollDownBtn.addEventListener('click', () => scrollToBottom('smooth'));

            // Focus on load
            window.addEventListener('load', () => {
                messageInput.focus();
                adjustTextareaHeight();
            });

            // If form is accidentally submitted, prevent
            document.querySelectorAll('form').forEach(f => f.addEventListener('submit', (e) => e.preventDefault()));
        </script>
    @endpush
</x-layouts::app>
