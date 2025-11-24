<?php include __DIR__ . '/../header.tpl'; ?>

<style>
    .dashboard-container {
        padding-block: 4rem;
        max-width: 800px;
        margin-inline: auto;
    }

    .alert-box {
        padding: 1rem 1.5rem;
        border-radius: var(--radius-md);
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        font-weight: 600;
    }
    .alert-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
    .alert-danger { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

    .details-card {
        padding: 0;
        overflow: hidden;
    }

    .details-header {
        padding: 1.5rem 2rem;
        background: rgba(255,255,255,0.5);
        border-bottom: 1px solid var(--color-border);
    }
    .details-title { font-size: 1.25rem; font-weight: 700; color: var(--color-text-main); }

    .detail-row {
        display: grid;
        grid-template-columns: 1fr;
        padding: 1.25rem 2rem;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    .detail-row:last-child { border-bottom: none; }

    @media (min-width: 640px) {
        .detail-row {
            grid-template-columns: 1fr 2fr;
            gap: 1rem;
        }
    }

    .detail-label { color: var(--color-text-muted); font-weight: 500; }
    .detail-value { color: var(--color-text-main); font-weight: 600; }

    .section-divider {
        padding: 1rem 2rem;
        background: rgba(0,0,0,0.02);
        font-weight: 700;
        color: var(--color-primary);
        font-size: 1.1rem;
        border-block: 1px solid rgba(0,0,0,0.05);
    }

    .back-link {
        display: inline-block;
        margin-top: 2rem;
        color: var(--color-primary);
        font-weight: 600;
        transition: var(--transition-smooth);
    }
    .back-link:hover { text-decoration: underline; text-underline-offset: 4px; }
</style>

<div class="dashboard-container">

    <?php if ($transaction->status === 'successful'): ?>
        <div class="alert-box alert-success">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            <span>پرداخت شما با موفقیت انجام شد</span>
        </div>
    <?php elseif ($transaction->status === 'failed'): ?>
        <div class="alert-box alert-danger">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
            <span>پرداخت ناموفق بود</span>
        </div>
    <?php endif; ?>

    <?php
    $payment_details = null;
    if (!empty($order->payment_gateway_response)) {
        $payment_details = json_decode($order->payment_gateway_response);
    }
    ?>

    <div class="glass-panel details-card">
        <div class="details-header">
            <h3 class="details-title">
                جزئیات سفارش #<?= htmlspecialchars($order->order_code) ?>
            </h3>
        </div>

        <dl>
            <div class="detail-row">
                <dt class="detail-label">وضعیت پرداخت</dt>
                <dd class="detail-value"><?= translate_status_fa($transaction->status) ?></dd>
            </div>
            <div class="detail-row">
                <dt class="detail-label">کد رهگیری داخلی</dt>
                <dd class="detail-value"><?= htmlspecialchars($transaction->track_id) ?></dd>
            </div>

            <?php if ($payment_details && isset($payment_details->refNumber)): ?>
            <div class="detail-row">
                <dt class="detail-label">شماره پیگیری درگاه</dt>
                <dd class="detail-value"><?= htmlspecialchars($payment_details->refNumber) ?></dd>
            </div>
            <?php endif; ?>

            <?php if ($payment_details && isset($payment_details->cardNumber)): ?>
            <div class="detail-row">
                <dt class="detail-label">شماره کارت</dt>
                <dd class="detail-value"><?= htmlspecialchars($payment_details->cardNumber) ?></dd>
            </div>
            <?php endif; ?>

            <div class="detail-row">
                <dt class="detail-label">مبلغ</dt>
                <dd class="detail-value" style="color: var(--color-success);"><?= htmlspecialchars(number_format($order->amount)) ?> تومان</dd>
            </div>

            <div class="detail-row">
                <dt class="detail-label">تاریخ ثبت سفارش</dt>
                <dd class="detail-value"><?= \jdate('Y/m/d H:i', strtotime($order->order_time)) ?></dd>
            </div>

            <div class="detail-row">
                <dt class="detail-label">نام محصول</dt>
                <dd class="detail-value"><?= htmlspecialchars($order->product_name) ?></dd>
            </div>

            <?php
            if (!empty($order->custom_fields_data)) {
                $custom_fields = json_decode($order->custom_fields_data);
                if (is_array($custom_fields) && !empty($custom_fields)) {
            ?>
                <div class="section-divider">
                     اطلاعات تکمیلی
                </div>

            <?php
                    foreach ($custom_fields as $index => $field_data) {
            ?>
                        <div class="detail-row">
                            <dt class="detail-label"><?= htmlspecialchars($field_data->label ?? $field_data->name) ?></dt>
                            <dd class="detail-value"><?= htmlspecialchars($field_data->value) ?></dd>
                        </div>
            <?php
                    }
                }
            }
            ?>

        </dl>
    </div>

    <div style="text-align: left;">
        <a href="/dashboard/orders" class="back-link">&larr; بازگشت به لیست سفارشات</a>
    </div>
</div>

<?php include __DIR__ . '/../footer.tpl'; ?>
