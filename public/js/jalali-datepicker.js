(function() {
    'use strict';

    // --- Jalali Helper Functions (Ported from php-jdf/jdf.php logic) ---

    const g_days_in_month = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    const j_days_in_month = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];

    function gregorianToJalali(g_y, g_m, g_d) {
        g_y = parseInt(g_y); g_m = parseInt(g_m); g_d = parseInt(g_d);
        var gy = g_y - 1600;
        var gm = g_m - 1;
        var gd = g_d - 1;

        var g_day_no = 365 * gy + Math.floor((gy + 3) / 4) - Math.floor((gy + 99) / 100) + Math.floor((gy + 399) / 400);

        for (var i = 0; i < gm; ++i) g_day_no += g_days_in_month[i];
        if (gm > 1 && ((gy % 4 == 0 && gy % 100 != 0) || (gy % 400 == 0))) g_day_no++;
        g_day_no += gd;

        var j_day_no = g_day_no - 79;
        var j_np = Math.floor(j_day_no / 12053);
        j_day_no %= 12053;

        var jy = 979 + 33 * j_np + 4 * Math.floor(j_day_no / 1461);
        j_day_no %= 1461;

        if (j_day_no >= 366) {
            jy += Math.floor((j_day_no - 1) / 365);
            j_day_no = (j_day_no - 1) % 365;
        }

        for (var i = 0; i < 11 && j_day_no >= j_days_in_month[i]; ++i) j_day_no -= j_days_in_month[i];
        var jm = i + 1;
        var jd = j_day_no + 1;

        return [jy, jm, jd];
    }

    function jalaliToGregorian(j_y, j_m, j_d) {
        j_y = parseInt(j_y); j_m = parseInt(j_m); j_d = parseInt(j_d);
        var jy = j_y - 979;
        var jm = j_m - 1;
        var jd = j_d - 1;

        var j_day_no = 365 * jy + Math.floor(jy / 33) * 8 + Math.floor(((jy % 33) + 3) / 4);
        for (var i = 0; i < jm; ++i) j_day_no += j_days_in_month[i];
        j_day_no += jd;

        var g_day_no = j_day_no + 79;
        var gy = 1600 + 400 * Math.floor(g_day_no / 146097);
        g_day_no = g_day_no % 146097;

        var leap = true;
        if (g_day_no >= 36525) {
            g_day_no--;
            gy += 100 * Math.floor(g_day_no / 36524);
            g_day_no = g_day_no % 36524;
            if (g_day_no >= 365) g_day_no++;
            else leap = false;
        }

        gy += 4 * Math.floor(g_day_no / 1461);
        g_day_no %= 1461;

        if (g_day_no >= 366) {
            leap = false;
            g_day_no--;
            gy += Math.floor(g_day_no / 365);
            g_day_no %= 365;
        }

        for (var i = 0; g_day_no >= g_days_in_month[i] + (i == 1 && leap); i++)
            g_day_no -= g_days_in_month[i] + (i == 1 && leap);

        var gm = i + 1;
        var gd = g_day_no + 1;

        return [gy, gm, gd];
    }

    function pad(n) { return n < 10 ? '0' + n : n; }

    // --- Jalali Datepicker Class ---

    class JalaliDatepicker {
        constructor(inputSelector, options = {}) {
            this.input = document.querySelector(inputSelector);
            if (!this.input) return;

            this.options = Object.assign({
                minDate: null,
                maxDate: null,
                initialValue: null // Gregorian Timestamp (ms) or Date object
            }, options);

            // State
            this.visible = false;
            this.now = new Date();
            // Default to now or initial value
            let initDate = this.options.initialValue ? new Date(this.options.initialValue) : null;
            if (!initDate || isNaN(initDate.getTime())) {
                initDate = new Date();
                this.hasValue = !!this.options.initialValue; // If explicit null passed, treat as empty
            } else {
                this.hasValue = true;
            }

            this.selectedDate = {
                gy: initDate.getFullYear(),
                gm: initDate.getMonth() + 1,
                gd: initDate.getDate(),
                h: initDate.getHours(),
                m: initDate.getMinutes(),
                s: initDate.getSeconds()
            };

            // View state (what month we are looking at)
            let jDate = gregorianToJalali(this.selectedDate.gy, this.selectedDate.gm, this.selectedDate.gd);
            this.viewYear = jDate[0];
            this.viewMonth = jDate[1];

            // Hidden input for timestamp
            this.hiddenInput = document.createElement('input');
            this.hiddenInput.type = 'hidden';
            this.hiddenInput.name = this.input.name; // Take name from visible input
            this.input.removeAttribute('name'); // Remove name from visible input
            this.input.readOnly = true; // Force readonly
            this.input.classList.add('cursor-pointer');
            this.input.parentNode.insertBefore(this.hiddenInput, this.input.nextSibling);

            // Initialize UI
            this.createUI();
            this.bindEvents();

            if (this.hasValue) {
                this.updateInput();
            }
        }

        createUI() {
            // Create overlay and container
            this.overlay = document.createElement('div');
            this.overlay.className = 'jdp-overlay';

            this.container = document.createElement('div');
            this.container.className = 'jdp-container';

            this.overlay.appendChild(this.container);
            document.body.appendChild(this.overlay);

            this.render();
        }

        render() {
            // Calculate days
            let daysInMonth = j_days_in_month[this.viewMonth - 1];
            if (this.viewMonth === 12 && this.isLeapJalali(this.viewYear)) daysInMonth = 30;

            // Determine start day of week (0 = Saturday in Jalali context usually, let's stick to standard JS Day 0=Sun)
            // But visual grid: Sat Sun Mon Tue Wed Thu Fri
            // Let's find what day of week the 1st of this Jalali month is.
            let gFirst = jalaliToGregorian(this.viewYear, this.viewMonth, 1);
            let firstDate = new Date(gFirst[0], gFirst[1] - 1, gFirst[2]);
            let startDay = (firstDate.getDay() + 1) % 7; // Shift so Saturday is 0

            const monthNames = ["فروردین", "اردیبهشت", "خرداد", "تیر", "مرداد", "شهریور", "مهر", "آبان", "آذر", "دی", "بهمن", "اسفند"];

            let html = `
                <div class="jdp-header">
                    <div class="jdp-year-month">
                        <button type="button" class="jdp-nav-btn" id="jdp-prev-month">‹</button>
                        <span>${monthNames[this.viewMonth - 1]} ${this.viewYear}</span>
                        <button type="button" class="jdp-nav-btn" id="jdp-next-month">›</button>
                    </div>
                </div>
                <div class="jdp-grid">
                    <div class="jdp-weekdays">
                        <span>ش</span><span>ی</span><span>د</span><span>س</span><span>چ</span><span>پ</span><span>ج</span>
                    </div>
                    <div class="jdp-days">
            `;

            // Empty slots
            for (let i = 0; i < startDay; i++) {
                html += `<div class="jdp-day empty"></div>`;
            }

            // Days
            let jSelected = gregorianToJalali(this.selectedDate.gy, this.selectedDate.gm, this.selectedDate.gd);

            // Today check
            let now = new Date();
            let jNow = gregorianToJalali(now.getFullYear(), now.getMonth() + 1, now.getDate());

            for (let d = 1; d <= daysInMonth; d++) {
                let isSelected = this.hasValue && jSelected[0] === this.viewYear && jSelected[1] === this.viewMonth && jSelected[2] === d;
                let isToday = jNow[0] === this.viewYear && jNow[1] === this.viewMonth && jNow[2] === d;

                let classes = 'jdp-day';
                if (isSelected) classes += ' selected';
                if (isToday) classes += ' today';

                html += `<div class="${classes}" data-day="${d}">${d}</div>`;
            }

            html += `</div></div>`;

            // Time Picker
            html += `
                <div class="jdp-time-picker">
                    <div class="jdp-time-row">
                        <span class="jdp-time-label">ساعت</span>
                        <div class="jdp-slider-container">
                            <input type="range" min="0" max="23" class="jdp-slider" id="jdp-hour-slider" value="${this.selectedDate.h}">
                        </div>
                        <input type="number" min="0" max="23" class="jdp-time-input" id="jdp-hour-input" value="${pad(this.selectedDate.h)}">
                    </div>
                    <div class="jdp-time-row">
                        <span class="jdp-time-label">دقیقه</span>
                        <div class="jdp-slider-container">
                            <input type="range" min="0" max="59" class="jdp-slider" id="jdp-minute-slider" value="${this.selectedDate.m}">
                        </div>
                        <input type="number" min="0" max="59" class="jdp-time-input" id="jdp-minute-input" value="${pad(this.selectedDate.m)}">
                    </div>
                </div>
            `;

            // Footer
            html += `
                <div class="jdp-footer">
                    <button type="button" class="jdp-btn jdp-btn-cancel" id="jdp-cancel">انصراف</button>
                    <span class="jdp-current-time">${pad(this.selectedDate.h)}:${pad(this.selectedDate.m)}</span>
                    <button type="button" class="jdp-btn jdp-btn-confirm" id="jdp-confirm">تایید</button>
                </div>
            `;

            this.container.innerHTML = html;
            this.bindDynamicEvents();
        }

        bindEvents() {
            this.input.addEventListener('click', (e) => {
                e.preventDefault();
                this.show();
            });

            this.input.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.show();
                }
            });

            this.overlay.addEventListener('click', (e) => {
                if (e.target === this.overlay) this.hide();
            });

            document.addEventListener('keydown', (e) => {
                if (!this.visible) return;
                if (e.key === 'Escape') this.hide();
                // Add more nav logic later if needed
            });
        }

        bindDynamicEvents() {
            // Navigation
            this.container.querySelector('#jdp-prev-month').onclick = () => this.changeMonth(-1);
            this.container.querySelector('#jdp-next-month').onclick = () => this.changeMonth(1);

            // Days
            this.container.querySelectorAll('.jdp-day:not(.empty)').forEach(el => {
                el.onclick = () => {
                    this.container.querySelectorAll('.jdp-day.selected').forEach(s => s.classList.remove('selected'));
                    el.classList.add('selected');
                    this.selectedDate.gd = parseInt(el.dataset.day);
                    // Convert back view year/month/day to gregorian for storage
                    let gDate = jalaliToGregorian(this.viewYear, this.viewMonth, parseInt(el.dataset.day));
                    this.selectedDate.gy = gDate[0];
                    this.selectedDate.gm = gDate[1];
                    this.selectedDate.gd = gDate[2];
                    this.hasValue = true;
                };
            });

            // Time Sync (Slider <-> Input)
            const hSlider = this.container.querySelector('#jdp-hour-slider');
            const hInput = this.container.querySelector('#jdp-hour-input');
            const mSlider = this.container.querySelector('#jdp-minute-slider');
            const mInput = this.container.querySelector('#jdp-minute-input');
            const timeDisplay = this.container.querySelector('.jdp-current-time');

            const updateTime = () => {
                this.selectedDate.h = parseInt(hSlider.value);
                this.selectedDate.m = parseInt(mSlider.value);
                timeDisplay.textContent = `${pad(this.selectedDate.h)}:${pad(this.selectedDate.m)}`;
            };

            hSlider.oninput = () => { hInput.value = pad(hSlider.value); updateTime(); };
            hInput.onchange = () => {
                let v = parseInt(hInput.value);
                if(v < 0) v = 0; if(v > 23) v = 23;
                hInput.value = pad(v); hSlider.value = v; updateTime();
            };

            mSlider.oninput = () => { mInput.value = pad(mSlider.value); updateTime(); };
            mInput.onchange = () => {
                let v = parseInt(mInput.value);
                if(v < 0) v = 0; if(v > 59) v = 59;
                mInput.value = pad(v); mSlider.value = v; updateTime();
            };

            // Actions
            this.container.querySelector('#jdp-cancel').onclick = () => this.hide();
            this.container.querySelector('#jdp-confirm').onclick = () => {
                this.updateInput();
                this.hide();
            };
        }

        changeMonth(delta) {
            this.viewMonth += delta;
            if (this.viewMonth > 12) {
                this.viewMonth = 1;
                this.viewYear++;
            } else if (this.viewMonth < 1) {
                this.viewMonth = 12;
                this.viewYear--;
            }
            this.render();
        }

        updateInput() {
            let jDate = gregorianToJalali(this.selectedDate.gy, this.selectedDate.gm, this.selectedDate.gd);
            let formatted = `${jDate[0]}/${pad(jDate[1])}/${pad(jDate[2])} ${pad(this.selectedDate.h)}:${pad(this.selectedDate.m)}:${pad(this.selectedDate.s)}`;
            this.input.value = formatted;

            // Create Date object to get timestamp
            let d = new Date(this.selectedDate.gy, this.selectedDate.gm - 1, this.selectedDate.gd, this.selectedDate.h, this.selectedDate.m, this.selectedDate.s);
            // Send as YYYY-MM-DD HH:mm:ss (Gregorian) as per backend requirement usually, OR timestamp
            // Prompt says: "هنگام ارسال فرم، timestamp دقیق به سرور ارسال شود"
            // Unix timestamp in seconds or ms? PHP usually takes Y-m-d H:i:s for SQL.
            // But "timestamp" usually means unix epoch.
            // Let's send a MySQL compatible Gregorian string Y-m-d H:i:s to be safe with the Controller logic I planned.
            // Actually, if I send unix timestamp, I need to convert in PHP.
            // Let's send YYYY-MM-DD HH:mm:ss (Gregorian). It's unambiguous.
            // Wait, the prompt says "timestamp". Let's send unix timestamp (seconds).
            this.hiddenInput.value = Math.floor(d.getTime() / 1000);

            // Trigger change event
            this.input.dispatchEvent(new Event('change'));
        }

        show() {
            this.visible = true;
            this.overlay.classList.add('visible');
            // Sync sliders to current state
            this.render();
        }

        hide() {
            this.visible = false;
            this.overlay.classList.remove('visible');
        }

        isLeapJalali(jy) {
            // Simple 33-year cycle approximation or exact algorithm
            // Using the logic from jdf.php port
            var r = jy % 33;
            return r===1 || r===5 || r===9 || r===13 || r===17 || r===22 || r===26 || r===30;
        }
    }

    window.JalaliDatepicker = JalaliDatepicker;

})();
