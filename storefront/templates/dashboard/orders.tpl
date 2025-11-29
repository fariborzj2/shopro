<?php include __DIR__ . '/../header.tpl'; ?>

<style>
    .dashboard-container {
        padding-block: 4rem;
        max-width: 1000px;
        margin-inline: auto;
    }
    .page-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 2rem;
        color: var(--color-text-main);
    }

    .orders-list {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .order-card {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        transition: var(--transition-smooth);
        position: relative;
        border-right: 4px solid transparent; /* Status indicator strip */
    }

    .order-card:hover {
        transform: translateX(-4px); /* Move left for RTL hover effect */
        background: white;
    }

    @media (min-width: 768px) {
        .order-card {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
    }

    .order-info h3 {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--color-primary);
    }

    .order-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        font-size: 0.9rem;
        color: var(--color-text-muted);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 2rem;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .status-success { background: rgba(16, 185, 129, 0.1); color: #059669; }
    .status-danger { background: rgba(239, 68, 68, 0.1); color: #dc2626; }
    .status-warning { background: rgba(245, 158, 11, 0.1); color: #d97706; }
    .status-info { background: rgba(59, 130, 246, 0.1); color: #2563eb; }

    /* Dynamic border colors based on status logic if possible, or just generic */
    .order-card.status-paid { border-right-color: #10b981; }
    .order-card.status-failed { border-right-color: #ef4444; }
    .order-card.status-pending { border-right-color: #f59e0b; }
</style>

<div class="dashboard-container">
    <h1 class="page-title">تاریخچه سفارشات</h1>

    <div class="orders-list">
        <?php foreach ($orders as $order): ?>
            <?php
                // Determine status class for styling
                $statusClass = 'status-warning';
                if ($order->payment_status == 'paid') $statusClass = 'status-success';
                if ($order->payment_status == 'failed' || $order->payment_status == 'phishing') $statusClass = 'status-danger';

                $borderClass = 'status-pending';
                if ($order->payment_status == 'paid') $borderClass = 'status-paid';
                if ($order->payment_status == 'failed') $borderClass = 'status-failed';
            ?>

            <a href="/dashboard/orders/<?= $order->id ?>" class="glass-panel order-card <?= $borderClass ?>">
                <div class="order-info">
                    <h3><?= htmlspecialchars($order->product_name) ?></h3>
                    <div class="order-meta">
                        <span>مبلغ: <?= htmlspecialchars(number_format($order->amount)) ?> تومان</span>
                        <span>•</span>
                        <span>تاریخ: <?= \jdate('Y/m/d', strtotime($order->order_time)) ?></span>
                    </div>
                </div>

                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    <span class="status-badge <?= $statusClass ?>">
                        <?= translate_payment_status_fa($order->payment_status) ?>
                    </span>

                    <?php
                        $orderStatusClass = 'status-info';
                        if ($order->order_status == 'completed') $orderStatusClass = 'status-success';
                        if ($order->order_status == 'cancelled' || $order->order_status == 'phishing') $orderStatusClass = 'status-danger';
                    ?>
                    <span class="status-badge <?= $orderStatusClass ?>">
                        <?= translate_order_status_fa($order->order_status) ?>
                    </span>
                </div>
            </a>
        <?php endforeach; ?>

        <?php if(empty($orders)): ?>
            <div class="glass-panel" style="text-align: center; padding: 3rem; color: var(--color-text-muted);">
                هنوز سفارشی ثبت نکرده‌اید.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../footer.tpl'; ?>
