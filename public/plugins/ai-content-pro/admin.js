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
            const topic = prompt('موضوع مقاله را برای تولید تایتل وارد کنید:');
            if (!topic) return;
            const originalText = btn.innerText;
            btn.innerText = '⏳ در حال تولید...';
            btn.disabled = true;

            generateSimpleAi('generate_article', { topic, options: { length: 'short', format: 'title' } }, (res) => {
                document.getElementById('title').value = res.replace(/<\/?[^>]+(>|$)/g, "").replace(/^"|"$/g, '').trim();
                btn.innerText = originalText;
                btn.disabled = false;
            });
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

            if (!content || content.length < 50) return alert('ابتدا محتوای مقاله را وارد کنید (حداقل ۵۰ کاراکتر).');

            const originalText = btn.innerText;
            btn.innerText = '⏳ در حال آنالیز...';
            btn.disabled = true;

            generateSimpleAi('generate_meta', { content }, (res) => {
                if (res.meta_title) document.getElementById('meta_title').value = res.meta_title;
                if (res.meta_description) document.getElementById('meta_description').value = res.meta_description;
                btn.innerText = originalText;
                btn.disabled = false;
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
                         if (!topic) return alert('لطفاً ابتدا عنوان مقاله را وارد کنید تا هوش مصنوعی بداند در چه موردی بنویسد.');

                         btn.innerHTML = '⏳ در حال نوشتن...';
                         btn.disabled = true;

                         generateSimpleAi('generate_article', { topic }, (res) => {
                             if (window.tinymce && tinymce.get(textarea.id)) {
                                 tinymce.get(textarea.id).setContent(res);
                             } else {
                                 textarea.value = res;
                             }
                             btn.innerHTML = '✅ محتوا تولید شد!';
                             setTimeout(() => {
                                 btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg> تولید مجدد / تکمیل';
                                 btn.disabled = false;
                             }, 3000);
                         });
                     };
                     container.parentNode.insertBefore(btn, container);
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
    .then(res => res.json())
    .then(data => {
        if (data.job_id) {
            pollSimpleStatus(data.job_id, callback);
        } else {
            alert('خطا در ارتباط با سرور');
        }
    })
    .catch(err => {
        console.error(err);
        alert('خطای سیستمی در درخواست هوش مصنوعی');
    });
}

function pollSimpleStatus(id, callback) {
    fetch(`/admin/api/ai/jobs/status/${id}`)
    .then(res => res.json())
    .then(job => {
        if (job.status === 'completed') {
            try {
                callback(JSON.parse(job.result));
            } catch(e) {
                callback(job.result);
            }
        } else if (job.status === 'failed') {
            alert('خطای هوش مصنوعی: ' + job.error_message);
        } else {
            setTimeout(() => pollSimpleStatus(id, callback), 2500);
        }
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

                generateSimpleAi('generate_reply', { comment_text: commentText }, (res) => {
                    const reply = typeof res === 'string' ? res : (res.reply || res.text || JSON.stringify(res));
                    btn.innerHTML = originalContent;
                    btn.disabled = false;

                    if (confirm('پیشنهاد هوش مصنوعی برای پاسخ:\n\n' + reply + '\n\nآیا می‌خواهید این پاسخ را ثبت کنید؟')) {
                        const editLink = row.querySelector('a[title="ویرایش"]').href;
                        window.location.href = editLink + (editLink.includes('?') ? '&' : '?') + 'ai_reply=' + encodeURIComponent(reply);
                    }
                });
            };

            actions.insertBefore(btn, actions.firstChild);
        }
    });
}
