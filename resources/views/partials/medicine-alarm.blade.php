@auth
@php
    $__matUser    = Auth::user();
    $__matPatient = $__matUser->patient ?? null;
    $__matShow    = ($__matUser->usertype === 'patient');
@endphp
@if($__matShow)
<style>
#matToast {
    position: fixed;
    bottom: 1.5rem;
    right: 1.5rem;
    z-index: 99999;
    width: 340px;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 8px 40px rgba(124,58,237,.3), 0 2px 8px rgba(0,0,0,.07);
    border-left: 5px solid #7c3aed;
    display: none;
    overflow: hidden;
    animation: matSlideIn .4s cubic-bezier(.34,1.56,.64,1);
}
@keyframes matSlideIn {
    from { transform: translateX(110%) scale(.92); opacity: 0; }
    to   { transform: translateX(0)    scale(1);   opacity: 1; }
}
.mat-top {
    background: linear-gradient(135deg, #7c3aed, #5b21b6);
    padding: .75rem 1rem;
    display: flex;
    align-items: center;
    gap: .6rem;
}
.mat-top-icon { font-size: 1.4rem; line-height: 1; flex-shrink: 0; }
.mat-top-text { flex: 1; min-width: 0; }
.mat-top-title {
    font-size: .88rem;
    font-weight: 800;
    color: #fff;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.mat-top-sub { font-size: .68rem; color: rgba(255,255,255,.75); margin-top: .1rem; }
.mat-x {
    background: rgba(255,255,255,.18);
    border: none;
    border-radius: 50%;
    width: 26px; height: 26px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: #fff; font-size: .72rem;
    flex-shrink: 0; transition: background .2s;
}
.mat-x:hover { background: rgba(255,255,255,.32); }
.mat-progress { height: 3px; background: #ede9fe; }
.mat-prog-bar {
    height: 100%;
    background: linear-gradient(to right, #7c3aed, #a78bfa);
    width: 100%;
    transition: none;
}
.mat-body { padding: .85rem 1rem; }
.mat-name {
    font-size: 1rem; font-weight: 800;
    color: #4c1d95; margin-bottom: .25rem;
}
.mat-dose {
    display: inline-block;
    font-size: .72rem; font-weight: 700;
    background: #ede9fe; color: #5b21b6;
    padding: .12rem .55rem; border-radius: 20px;
    margin-bottom: .5rem;
}
.mat-hint {
    font-size: .76rem; color: #64748b;
    line-height: 1.55; margin-bottom: .65rem;
}
.mat-queue {
    font-size: .68rem; color: #94a3b8;
    background: #f8fafc; border-radius: 7px;
    padding: .28rem .6rem; margin-bottom: .55rem;
    display: none;
}
.mat-btns { display: flex; gap: .5rem; }
.mat-btn-ok {
    flex: 1;
    background: linear-gradient(135deg, #7c3aed, #5b21b6);
    color: #fff; border: none; border-radius: 9px;
    padding: .55rem; font-size: .78rem; font-weight: 700;
    cursor: pointer; transition: filter .2s;
}
.mat-btn-ok:hover { filter: brightness(1.1); }
.mat-btn-snz {
    flex: 1;
    background: #f1f5f9; color: #374151;
    border: none; border-radius: 9px;
    padding: .55rem; font-size: .78rem; font-weight: 700;
    cursor: pointer; transition: background .2s;
}
.mat-btn-snz:hover { background: #e2e8f0; }
</style>

<div id="matToast">
    {{-- existing toast HTML unchanged --}}
</div>

<script>
(function () {
    'use strict';

    var _queue       = [];
    var _snooze      = null;
    var _autoDismiss = null;
    var _lastMinute  = null; // ✅ null — first load always polls

    var _shown = new Set(
        JSON.parse(sessionStorage.getItem('_matShown') || '[]')
    );
    function _saveShown() {
        sessionStorage.setItem('_matShown', JSON.stringify([..._shown]));
    }

    function _fmt12(t) {
        if (!t || t.length < 4) return t;
        var parts = t.split(':');
        var h = parseInt(parts[0]), m = parseInt(parts[1]);
        var ap = h >= 12 ? 'PM' : 'AM';
        return (h % 12 || 12) + ':' + String(m).padStart(2, '0') + ' ' + ap;
    }

    function _beep() {
        try {
            var ctx = new (window.AudioContext || window.webkitAudioContext)();
            [880, 1046, 880].forEach(function(f, i) {
                var o = ctx.createOscillator();
                var g = ctx.createGain();
                o.connect(g); g.connect(ctx.destination);
                o.frequency.value = f; o.type = 'sine';
                var t = ctx.currentTime + i * 0.24;
                g.gain.setValueAtTime(0.3, t);
                g.gain.exponentialRampToValueAtTime(0.001, t + 0.2);
                o.start(t); o.stop(t + 0.22);
            });
        } catch(e) {}
    }

    function _startBar(seconds) {
        var bar = document.getElementById('matBar');
        if (!bar) return;
        bar.style.transition = 'none';
        bar.style.width = '100%';
        void bar.offsetWidth;
        bar.style.transition = 'width ' + seconds + 's linear';
        bar.style.width = '0%';
    }

    function _show(medicine_name, dosage, time, id) {
        var toast = document.getElementById('matToast');
        if (!toast) return;

        document.getElementById('matTitle').textContent = '💊 ' + medicine_name;
        document.getElementById('matSub').textContent   = 'Scheduled at ' + _fmt12(time);
        document.getElementById('matName').textContent  = medicine_name;

        var doseEl = document.getElementById('matDose');
        if (dosage) {
            doseEl.textContent   = dosage;
            doseEl.style.display = 'inline-block';
        } else {
            doseEl.style.display = 'none';
        }

        var qEl = document.getElementById('matQueue');
        if (_queue.length > 0) {
            qEl.textContent   = '+ ' + _queue.length + ' more reminder(s) pending';
            qEl.style.display = 'block';
        } else {
            qEl.style.display = 'none';
        }

        toast.style.display = 'block';
        _startBar(30);
        _beep();

        // Browser notification
        if (typeof Notification !== 'undefined' && Notification.permission === 'granted') {
            new Notification('💊 ' + medicine_name, {
                body: (dosage ? dosage + ' — ' : '') + _fmt12(time),
                tag : 'mat-' + id + '-' + time,
                requireInteraction: true,
            });
        }

        clearTimeout(_autoDismiss);
        _autoDismiss = setTimeout(matDismiss, 30000);
    }

    window.matDismiss = function() {
        var t = document.getElementById('matToast');
        if (t) t.style.display = 'none';
        clearTimeout(_autoDismiss);
        clearTimeout(_snooze);
        if (_queue.length > 0) {
            var nx = _queue.shift();
            setTimeout(function() { _show(nx.medicine_name, nx.dosage, nx.time, nx.id); }, 700);
        }
    };

    window.matSnooze = function() {
        var name = document.getElementById('matName').textContent;
        var dose = document.getElementById('matDose').textContent;
        matDismiss();
        _snooze = setTimeout(function() { _show(name, dose, 'snoozed', 'snz'); }, 5 * 60 * 1000);
    };

    // ✅ CSRF token helper
    function _csrf() {
        var m = document.querySelector('meta[name="csrf-token"]');
        return m ? m.content : '';
    }

    function _poll() {
        var now  = new Date();
        var curr = String(now.getHours()).padStart(2,'0') + ':' +
                   String(now.getMinutes()).padStart(2,'0');

        // ✅ Skip only if SAME minute AND already polled (not on first load)
        if (_lastMinute !== null && curr === _lastMinute) return;
        _lastMinute = curr;

        fetch('{{ route("patient.medicine-reminders.due-now") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN'    : _csrf(),
                'Accept'          : 'application/json',
            },
            credentials: 'same-origin'
        })
        .then(function(r) {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json();
        })
        .then(function(data) {
            if (!data || !data.success || !data.count) return;

            var first = true;
            data.due.forEach(function(r) {
                var key = r.id + '|' + r.time;
                if (_shown.has(key)) return;
                _shown.add(key);
                _saveShown();
                if (first) { first = false; _show(r.medicine_name, r.dosage, r.time, r.id); }
                else        { _queue.push(r); }
            });

            if (typeof updateNotificationCount === 'function') updateNotificationCount();
        })
        .catch(function(e) {
            // Silent — network issues shouldn't break the page
            console.warn('[MedAlarm] poll error:', e.message);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        _poll();                      // immediate on load
        setInterval(_poll, 30000);    // every 30s
    });

}());
</script>

@endif
@endauth
