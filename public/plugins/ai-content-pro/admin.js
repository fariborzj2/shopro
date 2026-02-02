// AI Content Pro - Admin Integration
document.addEventListener('DOMContentLoaded', function() {

    // 1. Integration with Blog Post Form
    if (document.querySelector('form[action*="/blog/posts/"]')) {
        integrateBlogPostForm();
    }

    // 2. Integration with Comments List
    if (window.location.pathname.includes('/admin/blog/comments')) {
        integrateCommentsList();
    }

    // 3. Auto-fill AI Reply in Edit Comment page
    if (window.location.pathname.includes('/admin/blog/comments/edit')) {
        const urlParams = new URLSearchParams(window.location.search);
        const aiReply = urlParams.get('ai_reply');
        if (aiReply) {
            const replyTextarea = document.getElementById('reply_content');
            if (replyTextarea) {
                replyTextarea.value = aiReply;
                replyTextarea.scrollIntoView({ behavior: 'smooth' });
                replyTextarea.classList.add('ring-2', 'ring-green-500');
                setTimeout(() => replyTextarea.classList.remove('ring-2', 'ring-green-500'), 3000);
            }
        }
    }
});

function integrateBlogPostForm() {
    // Add button next to Title
    const titleLabel = document.querySelector('label[for="title"]');
    if (titleLabel) {
        addAiButton(titleLabel, '✨ تولید عنوان هوشمند', (btn) => {
            window.dispatchEvent(new CustomEvent('open-ai-prompt', {
                detail: {
                    title: 'تولید عنوان هوشمند',
                    placeholder: 'موضوع مقاله را وارد کنید (مثلاً: فواید قهوه)',
                    callback: (topic) => {
                        const originalText = btn.innerText;
                        btn.innerText = '⏳ ...';
                        btn.disabled = true;
                        showToast('در حال تولید عنوان توسط Gemini...', 'info');

                        generateSimpleAi('generate_article', { topic, options: { length: 'short', format: 'title' } }, (res) => {
                            document.getElementById('title').value = res.replace(/<\/?[^>]+(>|$)/g, "").replace(/^"|"$/g, '').trim();
                            btn.innerText = originalText;
                            btn.disabled = false;
                            showToast('عنوان با موفقیت تولید شد.', 'success');
                        });
                    }
                }
            }));
        });
    }

    // Add button next to SEO Title
    const metaTitleLabel = document.querySelector('label[for="meta_title"]');
    if (metaTitleLabel) {
        addAiButton(metaTitleLabel, '🚀 بهینه‌سازی سئو با AI', (btn) => {
            let content = '';
            if (window.tinymce && tinymce.get('content')) {
                content = tinymce.get('content').getContent();
            } else {
                content = document.getElementById('content').value;
            }

            if (!content || content.length < 50) return showToast('ابتدا حداقل ۵۰ کاراکتر محتوا بنویسید.', 'error');

            const originalText = btn.innerText;
            btn.innerText = '⏳ ...';
            btn.disabled = true;
            showToast('در حال آنالیز محتوا و تولید متا...', 'info');

            generateSimpleAi('generate_meta', { content }, (res) => {
                if (res.meta_title) document.getElementById('meta_title').value = res.meta_title;
                if (res.meta_description) document.getElementById('meta_description').value = res.meta_description;
                btn.innerText = originalText;
                btn.disabled = false;
                showToast('تگ‌های متا بروزرسانی شدند.', 'success');
            });
        });
    }

    // Add button for TinyMCE Editors
    setTimeout(() => {
        const editors = ['.tinymce-editor'];
        editors.forEach(selector => {
            document.querySelectorAll(selector).forEach(textarea => {
                const container = textarea.closest('.rounded-xl');
                if (container) {
                     const btn = document.createElement('button');
                     btn.type = 'button';
                     btn.className = 'mb-2 text-xs flex items-center gap-1 text-primary-600 font-bold hover:text-primary-800 transition-colors bg-primary-50 px-3 py-1.5 rounded-lg border border-primary-100 shadow-sm';
                     btn.innerHTML = `
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        تولید محتوا با هوش مصنوعی (Gemini)
                     `;
                     btn.onclick = () => {
                         const topic = document.getElementById('title').value;
                         if (!topic) return showToast('لطفاً ابتدا عنوان مقاله را وارد کنید.', 'error');

                         btn.innerHTML = '⏳ در حال جادو...';
                         btn.disabled = true;
                         showToast('Gemini در حال نگارش مقاله است، لطفاً شکیبا باشید...', 'info');

                         generateSimpleAi('generate_article', { topic }, (res) => {
                             if (window.tinymce && tinymce.get(textarea.id)) {
                                 tinymce.get(textarea.id).setContent(res);
                             } else {
                                 textarea.value = res;
                             }
                             btn.innerHTML = '✅ تولید شد!';
                             showToast('مقاله با موفقیت به ویرایشگر اضافه شد.', 'success');
                             setTimeout(() => {
                                 btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg> تولید مجدد';
                                 btn.disabled = false;
                             }, 3000);
                         });
                     };

                     // Add "Expand" button
                     const expandBtn = document.createElement('button');
                     expandBtn.type = 'button';
                     expandBtn.className = 'mb-2 mr-2 text-[10px] bg-white text-gray-600 px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors';
                     expandBtn.innerText = '✍️ ادامه نگارش متن انتخابی';
                     expandBtn.onclick = () => {
                         let selectedText = '';
                         if (window.tinymce && tinymce.get(textarea.id)) {
                             selectedText = tinymce.get(textarea.id).selection.getContent({format: 'text'});
                         }

                         if (!selectedText) return showToast('ابتدا بخشی از متن را انتخاب کنید.', 'error');

                         showToast('در حال توسعه متن توسط هوش مصنوعی...', 'info');
                         generateSimpleAi('generate_article', { topic: selectedText, options: { length: 'short', instruction: 'Continue writing this text.' } }, (res) => {
                             if (window.tinymce && tinymce.get(textarea.id)) {
                                 tinymce.get(textarea.id).selection.setContent(selectedText + ' ' + res);
                             }
                             showToast('متن با موفقیت ادامه یافت.', 'success');
                         });
                     };

                     const toolbar = document.createElement('div');
                     toolbar.className = 'flex items-center';
                     toolbar.appendChild(btn);
                     toolbar.appendChild(expandBtn);

                     container.parentNode.insertBefore(toolbar, container);
                }
            });
        });
    }, 1500);
}

function addAiButton(label, text, callback) {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'mr-3 text-[10px] bg-primary-600 text-white px-2.5 py-1 rounded-md hover:bg-primary-700 transition-all shadow-sm transform active:scale-95';
    btn.innerText = text;
    btn.onclick = (e) => { e.preventDefault(); callback(btn); };
    label.appendChild(btn);
}

function generateSimpleAi(type, payload, callback) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/admin/api/ai/jobs/create', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ type, payload })
    })
    .then(res => {
        if (!res.ok) {
            return res.json().then(err => { throw err; });
        }
        return res.json();
    })
    .then(data => {
        // 1. Update CSRF Token FIRST
        if (data.new_csrf_token) {
            updateCsrfToken(data.new_csrf_token);
        }

        // 2. Now trigger worker using the NEW token
        fetch('/admin/api/ai/process', { method: 'POST' })
            .then(res => res.json())
            .then(d => { if (d.new_csrf_token) updateCsrfToken(d.new_csrf_token); })
            .catch(e => console.error('Worker trigger failed', e));

        if (data.job_id) {
            pollSimpleStatus(data.job_id, callback);
        } else {
            showToast(data.message || data.error || 'خطا در ایجاد درخواست هوش مصنوعی', 'error');
        }
    })
    .catch(err => {
        console.error('AI Request Error:', err);
        showToast(err.message || 'خطای سیستمی در درخواست هوش مصنوعی', 'error');
    });
}

window.showToast = function(message, type = 'error') {
    window.dispatchEvent(new CustomEvent('show-toast', { detail: { message, type } }));
}

window.updateCsrfToken = function(token) {
    if (!token) return;
    const meta = document.querySelector('meta[name="csrf-token"]');
    if (meta) meta.setAttribute('content', token);
    document.querySelectorAll('input[name="csrf_token"]').forEach(input => input.value = token);
}

function pollSimpleStatus(id, callback) {
    fetch(`/admin/api/ai/jobs/status/${id}`)
    .then(res => {
        if (!res.ok) {
            return res.json().then(err => { throw err; });
        }
        return res.json();
    })
    .then(job => {
        if (job.status === 'completed') {
            try {
                callback(JSON.parse(job.result));
            } catch(e) {
                callback(job.result);
            }
        } else if (job.status === 'failed') {
            showToast('پردازش با خطا مواجه شد: ' + job.error_message, 'error');
        } else {
            setTimeout(() => pollSimpleStatus(id, callback), 2500);
        }
    })
    .catch(err => {
        console.error('Polling Error:', err);
        showToast(err.message || 'خطا در دریافت وضعیت کار هوش مصنوعی', 'error');
    });
}

function integrateCommentsList() {
    document.querySelectorAll('tr.group').forEach(row => {
        const actions = row.querySelector('.flex.items-center.justify-center.gap-3');
        if (actions) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'text-primary-600 hover:text-primary-900 p-1 rounded-md hover:bg-primary-50 transition-colors';
            btn.title = 'پاسخ هوشمند با AI';
            btn.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>`;

            btn.onclick = (e) => {
                e.preventDefault();
                const commentText = row.querySelector('td:nth-child(2)').innerText;
                const originalContent = btn.innerHTML;
                btn.innerHTML = '<span class="text-[10px] animate-pulse">...</span>';
                btn.disabled = true;
                showToast('در حال تولید پاسخ هوشمند...', 'info');

                generateSimpleAi('generate_reply', { comment_text: commentText }, (res) => {
                    const reply = typeof res === 'string' ? res : (res.reply || res.text || JSON.stringify(res));
                    btn.innerHTML = originalContent;
                    btn.disabled = false;

                    window.dispatchEvent(new CustomEvent('open-ai-suggestion', {
                        detail: {
                            title: 'پیشنهاد پاسخ هوشمند',
                            content: reply,
                            callback: (finalReply) => {
                                const editLink = row.querySelector('a[title="ویرایش"]').href;
                                window.location.href = editLink + (editLink.includes('?') ? '&' : '?') + 'ai_reply=' + encodeURIComponent(finalReply);
                            }
                        }
                    }));
                });
            };

            actions.insertBefore(btn, actions.firstChild);
        }
    });
}
