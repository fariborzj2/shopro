<div class="card overflow-hidden">
    <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
        <h3 class="font-bold text-gray-800">صندوق پیام</h3>
        <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
            پیام جدید
        </button>
    </div>

    <div class="divide-y divide-gray-100">
        <?php if(!empty($messages)): ?>
            <?php foreach($messages as $msg): ?>
            <a href="/dashboard/messages/<?php echo $msg['id']; ?>" class="block hover:bg-gray-50 transition-colors p-4 <?php echo $msg['is_read'] ? '' : 'bg-blue-50'; ?>">
                <div class="flex items-start justify-between">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 mb-1 <?php echo $msg['is_read'] ? '' : 'text-blue-700'; ?>">
                                <?php echo htmlspecialchars($msg['subject']); ?>
                            </h4>
                            <p class="text-sm text-gray-600 line-clamp-1"><?php echo mb_substr(strip_tags($msg['body']), 0, 100) . '...'; ?></p>
                        </div>
                    </div>
                    <span class="text-xs text-gray-400 whitespace-nowrap"><?php echo \jdate('j F', strtotime($msg['created_at'])); ?></span>
                </div>
            </a>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="p-12 text-center text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                <p>هیچ پیامی ندارید.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
